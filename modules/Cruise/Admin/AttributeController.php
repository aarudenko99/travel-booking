<?php
namespace Modules\Cruise\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\AdminController;
use Modules\Core\Models\Attributes;
use Modules\Core\Models\AttributesTranslation;
use Modules\Core\Models\Terms;
use Modules\Core\Models\TermsTranslation;

class AttributeController extends AdminController
{
    public function __construct()
    {
        $this->setActiveMenu('admin/module/cruise');
        parent::__construct();
    }

    public function index(Request $request)
    {
        $this->checkPermission('cruise_manage_attributes');
        $listAttr = Attributes::where("service", 'cruise');
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
                    'name' => __('Cruise'),
                    'url'  => 'admin/module/cruise'
                ],
                [
                    'name'  => __('Attributes'),
                    'class' => 'active'
                ],
            ]
        ];
        return view('Cruise::admin.attribute.index', $data);
    }

    public function edit(Request $request, $id)
    {
        $row = Attributes::find($id);
        if (empty($row)) {
            abort(404);
        }
        $translation = $row->translateOrOrigin($request->query('lang'));
        $this->checkPermission('cruise_manage_attributes');
        $data = [
            'translation'    => $translation,
            'enable_multi_lang'=>true,
            'rows'        => Attributes::where("service", 'cruise')->get(),
            'row'         => $row,
            'breadcrumbs' => [
                [
                    'name' => __('Cruise'),
                    'url'  => 'admin/module/cruise'
                ],
                [
                    'name' => __('Attributes'),
                    'url'  => 'admin/module/cruise/attribute'
                ],
                [
                    'name'  => __('Attributes: :name', ['name' => $row->name]),
                    'class' => 'active'
                ],
            ]
        ];
        return view('Cruise::admin.attribute.detail', $data);
    }

    public function store(Request $request)
    {
        $this->checkPermission('cruise_manage_attributes');
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
            $row->service = 'cruise';
        }
        $row->fill($request->input());
        $res = $row->saveOriginOrTranslation($request->input('lang'));
        if ($res) {
            return redirect()->back()->with('success', __('Attribute saved'));
        }
    }

    public function editAttrBulk(Request $request)
    {
        $this->checkPermission('cruise_manage_attributes');
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
        $this->checkPermission('cruise_manage_attributes');
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
                    'name' => __('Cruise'),
                    'url'  => 'admin/module/cruise'
                ],
                [
                    'name' => __('Attributes'),
                    'url'  => 'admin/module/cruise/attribute'
                ],
                [
                    'name'  => __('Attribute: :name', ['name' => $row->name]),
                    'class' => 'active'
                ],
            ]
        ];
        return view('Cruise::admin.terms.index', $data);
    }

    public function term_edit(Request $request, $id)
    {
        $this->checkPermission('cruise_manage_attributes');
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
                    'name' => __('Cruise'),
                    'url'  => 'admin/module/cruise'
                ],
                [
                    'name' => __('Attributes'),
                    'url'  => 'admin/module/cruise/attribute'
                ],
                [
                    'name' => $attr->name,
                    'url'  => 'admin/module/cruise/attribute/terms/' . $row->attr_id
                ],
                [
                    'name'  => __('Term: :name', ['name' => $row->name]),
                    'class' => 'active'
                ],
            ]
        ];
        return view('Cruise::admin.terms.detail', $data);
    }

    public function term_store(Request $request)
    {
        $this->checkPermission('cruise_manage_attributes');
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
        $res = $row->saveOriginOrTranslation($request->input('lang'));
        if ($res) {
            return redirect()->back()->with('success', __('Term saved'));
        }
    }

    public function editTermBulk(Request $request)
    {
        $this->checkPermission('cruise_manage_attributes');
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



}
