<?php
namespace Modules\Tour\Controllers;

use Modules\FrontendController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Tour\Models\Tour;
use Modules\Tour\Models\TourCategory;
use Modules\Tour\Models\TourTranslation;
use Modules\Location\Models\Location;
use Modules\Core\Models\Attributes;
use Modules\Tour\Models\TourTerm;
use Modules\Booking\Models\Booking;

class ManageTourController extends FrontendController
{
    public function manageTour(Request $request)
    {

        $this->checkPermission('tour_view');
        $user_id = Auth::id();
        $list_tour = Tour::where("create_user", $user_id)->orderBy('id', 'desc');
        $data = [
            'rows' => $list_tour->paginate(5),
            'breadcrumbs'        => [
                [
                    'name' => __('Manage Tours'),
                    'url'  => 'user/tour'
                ],
                [
                    'name'  => __('All'),
                    'class' => 'active'
                ],
            ],
            'page_title'         => __("Manage Tours"),
        ];
        return view('Tour::frontend.manageTour.index', $data);
    }

    public function createTour(Request $request)
    {

        $this->checkPermission('tour_create');
//        Auth::user()->can('create',Tour::class);
//        $vendor =  Auth::user()->vendorPlanData;
        $row = new Tour();
        $data = [
            'row'           => $row,
            'translation' => new TourTranslation(),
            'tour_category' => TourCategory::get()->toTree(),
            'tour_location' => Location::get()->toTree(),
            'attributes'    => Attributes::where('service', 'tour')->get(),
            'breadcrumbs'        => [
                [
                    'name' => __('Manage Tours'),
                    'url'  => 'user/tour'
                ],
                [
                    'name'  => __('Create'),
                    'class' => 'active'
                ],
            ],
            'page_title'         => __("Create Tours"),
        ];
        return view('Tour::frontend.manageTour.detail', $data);
    }

    public function editTour(Request $request, $id)
    {
        $this->checkPermission('tour_update');
        $user_id = Auth::id();
        $row = Tour::where("create_user", $user_id);
        $row = $row->find($id);
        if (empty($row)) {
            return redirect(route('tour.vendor.index'))->with('warning', __('Tour not found!'));
        }
        $translation = $row->translateOrOrigin($request->query('lang'));
        $data = [
            'translation'    => $translation,
            'row'           => $row,
            'tour_category' => TourCategory::get()->toTree(),
            'tour_location' => Location::get()->toTree(),
            'attributes'    => Attributes::where('service', 'tour')->get(),
            "selected_terms" => $row->tour_term->pluck('term_id'),
            'breadcrumbs'        => [
                [
                    'name' => __('Manage Tours'),
                    'url'  => 'user/tour'
                ],
                [
                    'name'  => __('Edit'),
                    'class' => 'active'
                ],
            ],
            'page_title'         => __("Edit Tours"),
        ];
        return view('Tour::frontend.manageTour.detail', $data);
    }

    public function store( Request $request, $id ){

        $user = Auth::user();
        $user->can('create',Tour::class);

        if($id>0){
            $this->checkPermission('tour_update');
            $row = Tour::find($id);
            if (empty($row)) {
                return redirect(route('tour.vendor.edit',['id'=>$row->id]));
            }

            if($row->create_user != Auth::id() and !$this->hasPermission('tour_manage_others'))
            {
                return redirect(route('tour.vendor.edit',['id'=>$row->id]));
            }

        }else{
            $this->checkPermission('tour_create');
            $row = new Tour();
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
//        if(!empty(Auth::user()->vendorPlanData['tour']['auto_publish'])){
//            $row->status ='publish';
//        }


        $res = $row->saveOriginOrTranslation($request->input('lang'),true);
        if ($res) {
            if(!$request->input('lang') or is_default_lang($request->input('lang'))) {
                $this->saveTerms($row, $request);
            }
            $row->saveMeta($request);
            if($id > 0 ){
                return back()->with('success',  __('Tour updated') );
            }else{
                return redirect(route('tour.vendor.edit',['id'=>$row->id]))->with('success', __('Tour created') );
            }
        }
    }

    public function saveTerms($row, $request)
    {
        if (empty($request->input('terms'))) {
            TourTerm::where('tour_id', $row->id)->delete();
        } else {
            $term_ids = $request->input('terms');
            foreach ($term_ids as $term_id) {
                TourTerm::firstOrCreate([
                    'term_id' => $term_id,
                    'tour_id' => $row->id
                ]);
            }
            TourTerm::where('tour_id', $row->id)->whereNotIn('term_id', $term_ids)->delete();
        }
    }

    public function deleteTour($id)
    {
        $this->checkPermission('tour_delete');
        $user_id = Auth::id();
        Tour::where("create_user", $user_id)->where("id", $id)->first()->delete();
        return redirect(route('tour.vendor.index'))->with('success', __('Delete tour success!'));
    }

    public function bookingReport(Request $request)
    {
        $data = [
            'bookings' => Booking::getBookingHistory($request->input('status'), false ,Auth::id() , 'tour'),
            'statues'  => config('booking.statuses'),
            'breadcrumbs'        => [
                [
                    'name' => __('Manage Tours'),
                    'url'  => 'user/tour'
                ],
                [
                    'name'  => __('Booking Report'),
                    'class' => 'active'
                ],
            ],
            'page_title'         => __("Booking Report"),
        ];
        return view('Tour::frontend.manageTour.bookingReport', $data);
    }
}
