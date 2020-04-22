<?php
namespace Modules\Cruise\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\AdminController;
use Modules\Core\Models\Attributes;
use Modules\Cruise\Models\CruiseTerm;
use Modules\Cruise\Models\Cruise;
use Modules\Cruise\Models\CruiseCategory;
use Modules\Cruise\Models\CruiseTranslation;
use Modules\Location\Models\Location;

class CruiseController extends AdminController
{
    protected $cruise;
    protected $cruise_translation;
    protected $cruise_category;
    protected $cruise_term;
    protected $attributes;
    protected $location;

    public function __construct()
    {
        parent::__construct();
        $this->setActiveMenu('admin/module/cruise');
        $this->cruise = Cruise::class;
        $this->cruise_translation = CruiseTranslation::class;
        $this->cruise_category = CruiseCategory::class;
        $this->cruise_term = CruiseTerm::class;
        $this->attributes = Attributes::class;
        $this->location = Location::class;
    }

    public function index(Request $request)
    {
        $this->checkPermission('cruise_view');
        $query = $this->cruise::query() ;
        $query->orderBy('id', 'desc');
        if (!empty($cruise_name = $request->input('s'))) {
            $query->where('title', 'LIKE', '%' . $cruise_name . '%');
            $query->orderBy('title', 'asc');
        }
        if (!empty($cate = $request->input('cate_id'))) {
            $query->where('category_id', $cate);
        }
        if ($this->hasPermission('cruise_manage_others')) {
            if (!empty($author = $request->input('vendor_id'))) {
                $query->where('create_user', $author);
            }
        } else {
            $query->where('create_user', Auth::id());
        }
        $data = [
            'rows'               => $query->with(['getAuthor','category_cruise'])->paginate(20),
            'cruise_categories'    => $this->cruise_category::where('status', 'publish')->get()->toTree(),
            'cruise_manage_others' => $this->hasPermission('cruise_manage_others'),
            'page_title'=>__("Cruise Management"),
            'breadcrumbs'        => [
                [
                    'name' => __('Cruises'),
                    'url'  => 'admin/module/cruise'
                ],
                [
                    'name'  => __('All'),
                    'class' => 'active'
                ],
            ]
        ];
        return view('Cruise::admin.index', $data);
    }

    public function create(Request $request)
    {
        $this->checkPermission('cruise_create');
        $row = new Cruise();
        $row->fill([
            'status' => 'publish'
        ]);
        $data = [
            'row'           => $row,
            'attributes'    => $this->attributes::where('service', 'cruise')->get(),
            'cruise_category' => $this->cruise_category::where('status', 'publish')->get()->toTree(),
            'cruise_location' => $this->location::where('status', 'publish')->get()->toTree(),
            'translation' => new $this->cruise_translation(),
            'breadcrumbs'   => [
                [
                    'name' => __('Cruises'),
                    'url'  => 'admin/module/cruise'
                ],
                [
                    'name'  => __('Add Cruise'),
                    'class' => 'active'
                ],
            ]
        ];
        return view('Cruise::admin.detail', $data);
    }

    public function edit(Request $request, $id)
    {
        $this->checkPermission('cruise_update');
        $row = $this->cruise::find($id);
        if (empty($row)) {
            return redirect('admin/module/cruise');
        }
        $translation = $row->translateOrOrigin($request->query('lang'));
        if (!$this->hasPermission('cruise_manage_others')) {
            if ($row->create_user != Auth::id()) {
                return redirect('admin/module/cruise');
            }
        }
        $data = [
            'row'            => $row,
            'translation'    => $translation,
            "selected_terms" => $row->cruise_term->pluck('term_id'),
            'attributes'     => $this->attributes::where('service', 'cruise')->get(),
            'cruise_category'  => $this->cruise_category::where('status', 'publish')->get()->toTree(),
            'cruise_location'  => $this->location::where('status', 'publish')->get()->toTree(),
            'enable_multi_lang'=>true,
            'breadcrumbs'    => [
                [
                    'name' => __('Cruises'),
                    'url'  => 'admin/module/cruise'
                ],
                [
                    'name'  => __('Edit Cruise'),
                    'class' => 'active'
                ],
            ]
        ];
        return view('Cruise::admin.detail', $data);
    }

    public function store( Request $request, $id ){

        if($id>0){
            $this->checkPermission('cruise_update');
            $row = $this->cruise::find($id);
            if (empty($row)) {
                return redirect(route('cruise.admin.index'));
            }
            if($row->create_user != Auth::id() and !$this->hasPermission('cruise_manage_others'))
            {
                return redirect(route('space.admin.index'));
            }

        }else{
            $this->checkPermission('cruise_create');
            $row = new $this->cruise();
            $row->status = "publish";
        }
        $row->fill($request->input());
        $row->create_user = $request->input('create_user');
        $row->default_state = $request->input('default_state',1);
        $res = $row->saveOriginOrTranslation($request->input('lang'),true);
        if ($res) {
            if(!$request->input('lang') or is_default_lang($request->input('lang'))) {
                $this->saveTerms($row, $request);
            }
            $row->saveMeta($request);
            if($id > 0 ){
                return back()->with('success',  __('Cruise updated') );
            }else{
                return redirect(route('cruise.admin.edit',$row->id))->with('success', __('Cruise created') );
            }
        }
    }

    public function saveTerms($row, $request)
    {
        if (empty($request->input('terms'))) {
            $this->cruise_term::where('cruise_id', $row->id)->delete();
        } else {
            $term_ids = $request->input('terms');
            foreach ($term_ids as $term_id) {
                $this->cruise_term::firstOrCreate([
                    'term_id' => $term_id,
                    'cruise_id' => $row->id
                ]);
            }
            $this->cruise_term::where('cruise_id', $row->id)->whereNotIn('term_id', $term_ids)->delete();
        }
    }

    public function bulkEdit(Request $request)
    {

        $ids = $request->input('ids');
        $action = $request->input('action');
        if (empty($ids) or !is_array($ids)) {
            return redirect()->back()->with('error', __('No items selected!'));
        }
        if (empty($action)) {
            return redirect()->back()->with('error', __('Please select an action!'));
        }

        switch ($action){
            case "delete":
                foreach ($ids as $id) {
                    $query = $this->cruise::where("id", $id);
                    if (!$this->hasPermission('cruise_manage_others')) {
                        $query->where("create_user", Auth::id());
                        $this->checkPermission('cruise_delete');
                    }
                    $query->first()->delete();
                }
                return redirect()->back()->with('success', __('Deleted success!'));
                break;
            case "clone":
                $this->checkPermission('cruise_create');
                foreach ($ids as $id) {
                    (new $this->cruise())->saveCloneByID($id);
                }
                return redirect()->back()->with('success', __('Clone success!'));
                break;
            default:
                // Change status
                foreach ($ids as $id) {
                    $query = $this->cruise::where("id", $id);
                    if (!$this->hasPermission('cruise_manage_others')) {
                        $query->where("create_user", Auth::id());
                        $this->checkPermission('cruise_update');
                    }
                    $query->update(['status' => $action]);
                }
                return redirect()->back()->with('success', __('Update success!'));
                break;
        }
    }
}
