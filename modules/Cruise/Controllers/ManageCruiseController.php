<?php
namespace Modules\Cruise\Controllers;

use Modules\FrontendController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Cruise\Models\Cruise;
use Modules\Cruise\Models\CruiseCategory;
use Modules\Cruise\Models\CruiseTranslation;
use Modules\Location\Models\Location;
use Modules\Core\Models\Attributes;
use Modules\Cruise\Models\CruiseTerm;
use Modules\Booking\Models\Booking;

class ManageCruiseController extends FrontendController
{
    public function manageCruise(Request $request)
    {

        $this->checkPermission('cruise_view');
        $user_id = Auth::id();
        $list_cruise = Cruise::where("create_user", $user_id)->orderBy('id', 'desc');
        $data = [
            'rows' => $list_cruise->paginate(5),
            'breadcrumbs'        => [
                [
                    'name' => __('Manage Cruises'),
                    'url'  => 'user/cruise'
                ],
                [
                    'name'  => __('All'),
                    'class' => 'active'
                ],
            ],
            'page_title'         => __("Manage Cruises"),
        ];
        return view('Cruise::frontend.manageCruise.index', $data);
    }

    public function createCruise(Request $request)
    {

        $this->checkPermission('cruise_create');
//        Auth::user()->can('create',Cruise::class);
//        $vendor =  Auth::user()->vendorPlanData;
        $row = new Cruise();
        $data = [
            'row'           => $row,
            'translation' => new CruiseTranslation(),
            'cruise_category' => CruiseCategory::get()->toTree(),
            'cruise_location' => Location::get()->toTree(),
            'attributes'    => Attributes::where('service', 'cruise')->get(),
            'breadcrumbs'        => [
                [
                    'name' => __('Manage Cruises'),
                    'url'  => 'user/cruise'
                ],
                [
                    'name'  => __('Create'),
                    'class' => 'active'
                ],
            ],
            'page_title'         => __("Create Cruises"),
        ];
        return view('Cruise::frontend.manageCruise.detail', $data);
    }

    public function editCruise(Request $request, $id)
    {
        $this->checkPermission('cruise_update');
        $user_id = Auth::id();
        $row = Cruise::where("create_user", $user_id);
        $row = $row->find($id);

        dd($row);
        if (empty($row)) {
            return redirect(route('cruise.vendor.index'))->with('warning', __('Cruise not found!'));
        }
        $translation = $row->translateOrOrigin($request->query('lang'));
        $data = [
            'translation'    => $translation,
            'row'           => $row,
            'cruise_category' => CruiseCategory::get()->toTree(),
            'cruise_location' => Location::get()->toTree(),
            'attributes'    => Attributes::where('service', 'cruise')->get(),
            "selected_terms" => $row->cruise_term->pluck('term_id'),
            'breadcrumbs'        => [
                [
                    'name' => __('Manage Cruises'),
                    'url'  => 'user/cruise'
                ],
                [
                    'name'  => __('Edit'),
                    'class' => 'active'
                ],
            ],
            'page_title'         => __("Edit Cruises"),
        ];
        return view('Cruise::frontend.manageCruise.detail', $data);
    }

    public function store( Request $request, $id ){

        $user = Auth::user();
        $user->can('create',Cruise::class);

        if($id>0){
            $this->checkPermission('cruise_update');
            $row = Cruise::find($id);
            if (empty($row)) {
                return redirect(route('cruise.vendor.edit',['id'=>$row->id]));
            }

            if($row->create_user != Auth::id() and !$this->hasPermission('cruise_manage_others'))
            {
                return redirect(route('cruise.vendor.edit',['id'=>$row->id]));
            }

        }else{
            $this->checkPermission('cruise_create');
            $row = new Cruise();
            $row->status = "draft";
        }

        $row->fillByAttr([
            'title',
            'content',
            'image_id',
            'banner_image_id',
            'short_desc',
            'category_id',
            'location_id',
            'address',
            'map_lat',
            'map_lng',
            'map_zoom',
            'gallery',
            'video',
            'price',
            'sale_price',
            'duration',
            'max_people',
            'min_people',
            'faqs'
        ], $request->input());

//        check autoPublish vendor
//        if(!empty(Auth::user()->vendorPlanData['cruise']['auto_publish'])){
//            $row->status ='publish';
//        }


        $res = $row->saveOriginOrTranslation($request->input('lang'),true);
        if ($res) {
            if(!$request->input('lang') or is_default_lang($request->input('lang'))) {
                $this->saveTerms($row, $request);
            }
            $row->saveMeta($request);
            if($id > 0 ){
                return back()->with('success',  __('Cruise updated') );
            }else{
                return redirect(route('cruise.vendor.edit',['id'=>$row->id]))->with('success', __('Cruise created') );
            }
        }
    }

    public function saveTerms($row, $request)
    {
        if (empty($request->input('terms'))) {
            CruiseTerm::where('cruise_id', $row->id)->delete();
        } else {
            $term_ids = $request->input('terms');
            foreach ($term_ids as $term_id) {
                CruiseTerm::firstOrCreate([
                    'term_id' => $term_id,
                    'cruise_id' => $row->id
                ]);
            }
            CruiseTerm::where('cruise_id', $row->id)->whereNotIn('term_id', $term_ids)->delete();
        }
    }

    public function deleteCruise($id)
    {
        $this->checkPermission('cruise_delete');
        $user_id = Auth::id();
        Cruise::where("create_user", $user_id)->where("id", $id)->first()->delete();
        return redirect(route('cruise.vendor.index'))->with('success', __('Delete cruise success!'));
    }

    public function bookingReport(Request $request)
    {
        $data = [
            'bookings' => Booking::getBookingHistory($request->input('status'), false ,Auth::id() , 'cruise'),
            'statues'  => config('booking.statuses'),
            'breadcrumbs'        => [
                [
                    'name' => __('Manage Cruises'),
                    'url'  => 'user/cruise'
                ],
                [
                    'name'  => __('Booking Report'),
                    'class' => 'active'
                ],
            ],
            'page_title'         => __("Booking Report"),
        ];
        return view('Cruise::frontend.manageCruise.bookingReport', $data);
    }
}
