<?php
namespace Modules\Cruise\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\AdminController;
use Modules\Cruise\Models\Cruise;
use Modules\Cruise\Models\CruiseCategory;
use Modules\Cruise\Models\CruiseCategoryTranslation;

class CategoryController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->setActiveMenu('admin/module/cruise');
    }

    public function index(Request $request)
    {
        $this->checkPermission('cruise_manage_others');
        $listCategory = CruiseCategory::query();
        if (!empty($search = $request->query('s'))) {
            $listCategory->where('name', 'LIKE', '%' . $search . '%');
        }
        $listCategory->orderBy('created_at', 'desc');
        $data = [
            'rows'        => $listCategory->get()->toTree(),
            'row'         => new CruiseCategory(),
            'translation'    => new CruiseCategoryTranslation(),
            'breadcrumbs' => [
                [
                    'name' => __('Cruise'),
                    'url'  => 'admin/module/cruise'
                ],
                [
                    'name'  => __('Category'),
                    'class' => 'active'
                ],
            ]
        ];
        return view('Cruise::admin.category.index', $data);
    }

    public function edit(Request $request, $id)
    {
        $this->checkPermission('cruise_manage_others');
        $row = CruiseCategory::find($id);
        if (empty($row)) {
            return redirect(route('cruise.admin.category.index'));
        }
        $translation = $row->translateOrOrigin($request->query('lang'));
        $data = [
            'translation'    => $translation,
            'enable_multi_lang'=>true,
            'row'         => $row,
            'parents'     => CruiseCategory::get()->toTree(),
            'breadcrumbs' => [
                [
                    'name' => __('Cruise'),
                    'url'  => 'admin/module/cruise'
                ],
                [
                    'name'  => __('Category'),
                    'class' => 'active'
                ],
            ]
        ];
        return view('Cruise::admin.category.detail', $data);
    }

    public function store(Request $request , $id)
    {
        $this->checkPermission('cruise_manage_others');
        $this->validate($request, [
            'name' => 'required'
        ]);
        if($id>0){
            $row = CruiseCategory::find($id);
            if (empty($row)) {
                return redirect(route('cruise.admin.category.index'));
            }
        }else{
            $row = new CruiseCategory();
            $row->status = "publish";
        }

        $row->fill($request->input());
        $res = $row->saveOriginOrTranslation($request->input('lang'),true);

        if ($res) {
            return back()->with('success',  __('Category saved') );
        }
    }

    public function editBulk(Request $request)
    {
        $this->checkPermission('cruise_manage_others');
        $ids = $request->input('ids');
        $action = $request->input('action');
        if (empty($ids) or !is_array($ids)) {
            return redirect()->back()->with('error', __('Select at least 1 item!'));
        }
        if (empty($action)) {
            return redirect()->back()->with('error', __('Select an Action!'));
        }
        if ($action == "delete") {
            foreach ($ids as $id) {
                $query = CruiseCategory::where("id", $id);
                $query->first()->delete();
            }
        } else {
            foreach ($ids as $id) {
                $query = CruiseCategory::where("id", $id);
                $query->update(['status' => $action]);
            }
        }
        return redirect()->back()->with('success', __('Updated success!'));
    }

    public function getForSelect2(Request $request)
    {
        $pre_selected = $request->query('pre_selected');
        $selected = $request->query('selected');

        if($pre_selected && $selected){
            $item = CruiseCategory::find($selected);
            if(empty($item)){
                return response()->json([
                    'text'=>''
                ]);
            }else{
                return response()->json([
                    'text'=>$item->name
                ]);
            }
        }
        $q = $request->query('q');
        $query = CruiseCategory::select('id', 'name as text')->where("status","publish");
        if ($q) {
            $query->where('name', 'like', '%' . $q . '%');
        }
        $res = $query->orderBy('id', 'desc')->limit(20)->get();
        return response()->json([
            'results' => $res
        ]);
    }
}
