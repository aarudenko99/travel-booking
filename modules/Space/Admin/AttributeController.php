<?php
namespace Modules\Space\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\AdminController;
use Modules\Core\Models\Attributes;
use Modules\Core\Models\AttributesTranslation;
use Modules\Core\Models\Terms;
use Modules\Core\Models\TermsTranslation;
use Illuminate\Support\Facades\DB;

class AttributeController extends AdminController
{
    public function __construct()
    {
        $this->setActiveMenu('admin/module/space');
        parent::__construct();
    }

    public function index(Request $request)
    {
        $this->checkPermission('space_manage_attributes');
        $listAttr = Attributes::where("service", 'space');
        if (!empty($search = $request->query('s'))) {
            $listAttr->where('name', 'LIKE', '%' . $search . '%');
        }
        $listAttr->orderBy('created_at', 'desc');
        $data = [
            'rows'        => $listAttr->get(),
            'row'         => new Attributes(),
            'translation'    => new AttributesTranslation(),
            'breadcrumbs' => [
                [
                    'name' => __('Space'),
                    'url'  => 'admin/module/space'
                ],
                [
                    'name'  => __('Attributes'),
                    'class' => 'active'
                ],
            ]
        ];
        return view('Space::admin.attribute.index', $data);
    }

    public function edit(Request $request, $id)
    {
        $row = Attributes::find($id);
        if (empty($row)) {
            abort(404);
        }
        $translation = $row->translateOrOrigin($request->query('lang'));
        $this->checkPermission('space_manage_attributes');
        $data = [
            'translation'    => $translation,
            'enable_multi_lang'=>true,
            'rows'        => Attributes::where("service", 'space')->get(),
            'row'         => $row,
            'breadcrumbs' => [
                [
                    'name' => __('Space'),
                    'url'  => 'admin/module/space'
                ],
                [
                    'name' => __('Attributes'),
                    'url'  => 'admin/module/space/attribute'
                ],
                [
                    'name'  => __('Attribute: :name', ['name' => $row->name]),
                    'class' => 'active'
                ],
            ]
        ];
        return view('Space::admin.attribute.detail', $data);
    }

    public function store(Request $request)
    {
        $this->checkPermission('space_manage_attributes');
        $this->validate($request, [
            'name' => 'required'
        ]);
        $id = $request->input('id');
        if ($id) {
            $row = Attributes::find($id);
            if (empty($row)) {
                abort(404);
            }
        } else {
            $row = new Attributes($request->input());
            $row->service = 'space';
        }
        $row->fill($request->input());
        $res = $row->saveOriginOrTranslation($request->input('lang'));
        if ($res) {
            return redirect()->back()->with('success', __('Attribute saved'));
        }
    }

    public function editAttrBulk(Request $request)
    {
        $this->checkPermission('space_manage_attributes');
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
                $query = Attributes::where("id", $id);
                $query->first()->delete();
            }
        }
        return redirect()->back()->with('success', __('Updated success!'));
    }

    public function terms(Request $request, $attr_id)
    {
        $this->checkPermission('space_manage_attributes');
        $row = Attributes::find($attr_id);
        if (empty($row)) {
            abort(404);
        }
        $listTerms = Terms::where("attr_id", $attr_id);
        if (!empty($search = $request->query('s'))) {
            $listTerms->where('name', 'LIKE', '%' . $search . '%');
        }
        $listTerms->orderBy('created_at', 'desc');
        $data = [
            'rows'        => $listTerms->paginate(20),
            'attr'        => $row,
            "row"         => new Terms(),
            'translation'    => new TermsTranslation(),
            'breadcrumbs' => [
                [
                    'name' => __('Space'),
                    'url'  => 'admin/module/space'
                ],
                [
                    'name' => __('Attributes'),
                    'url'  => 'admin/module/space/attribute'
                ],
                [
                    'name'  => __('Attribute: :name', ['name' => $row->name]),
                    'class' => 'active'
                ],
            ]
        ];
        return view('Space::admin.terms.index', $data);
    }

    public function term_edit(Request $request, $id)
    {
        $this->checkPermission('space_manage_attributes');
        $row = Terms::find($id);
        if (empty($row)) {
            return redirect()->back()->with('error', __('Term not found'));
        }
        $translation = $row->translateOrOrigin($request->query('lang'));
        $attr = Attributes::find($row->attr_id);
        $data = [
            'row'         => $row,
            'translation'    => $translation,
            'enable_multi_lang'=>true,
            'breadcrumbs' => [
                [
                    'name' => __('Space'),
                    'url'  => 'admin/module/space'
                ],
                [
                    'name' => __('Attributes'),
                    'url'  => 'admin/module/space/attribute'
                ],
                [
                    'name' => $attr->name,
                    'url'  => 'admin/module/space/attribute/terms/' . $row->attr_id
                ],
                [
                    'name'  => __('Term: :name', ['name' => $row->name]),
                    'class' => 'active'
                ],
            ]
        ];
        return view('Space::admin.terms.detail', $data);
    }

    public function term_store(Request $request)
    {
        $this->checkPermission('space_manage_attributes');
        $this->validate($request, [
            'name' => 'required'
        ]);
        $id = $request->input('id');
        if ($id) {
            $row = Terms::find($id);
            if (empty($row)) {
                abort(404);
            }
        } else {
            $row = new Terms($request->input());
            $row->attr_id = $request->input('attr_id');
        }
        $row->fill($request->input());
        $row->image_id = $request->input('image_id');
        $res = $row->saveOriginOrTranslation($request->input('lang'));
        if ($res) {
            return redirect()->back()->with('success', __('Term saved'));
        }
    }

    public function editTermBulk(Request $request)
    {
        $this->checkPermission('space_manage_attributes');
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
                $query = Terms::where("id", $id);
                $query->first()->delete();
            }
        }
        return redirect()->back()->with('success', __('Updated success!'));
    }

    public function getForSelect2(Request $request)
    {
        $pre_selected = $request->query('pre_selected');
        $selected = $request->query('selected');

        if($pre_selected && $selected){
            if(is_array($selected))
            {
                $query = Terms::getForSelect2Query('space');
                $items = $query->whereIn('bravo_terms.id',$selected)->take(50)->get();
                return response()->json([
                    'items'=>$items
                ]);
            }
            
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
        $query = Terms::getForSelect2Query('space',$q);
        $res = $query->orderBy('bravo_terms.id', 'desc')->limit(20)->get();
        return response()->json([
            'results' => $res
        ]);
    }
}
