<?php
namespace Modules\Space\Controllers;

use Modules\FrontendController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Space\Models\Space;

use Modules\Tour\Models\TourCategory;
use Modules\Tour\Models\TourTranslation;
use Modules\Location\Models\Location;
use Modules\Core\Models\Attributes;
use Modules\Tour\Models\TourTerm;
use Modules\Booking\Models\Booking;


use Modules\Space\Models\SpaceTerm;
use Modules\Space\Models\SpaceTranslation;

class ManageSpaceController extends FrontendController
{
    protected $space;
    protected $space_translation;
    protected $space_term;
    public function __construct()
    {
        parent::__construct();
        $this->space = Space::class;
        $this->space_translation = SpaceTranslation::class;
        $this->space_term = SpaceTerm::class;
    }

    public function manageSpace(Request $request)
    {
        $this->checkPermission('space_view');
        $user_id = Auth::id();
        $list_tour = $this->space::where("create_user", $user_id)->orderBy('id', 'desc');
        $data = [
            'rows' => $list_tour->paginate(5),
            'breadcrumbs'        => [
                [
                    'name' => __('Manage Spaces'),
                    'url'  => 'user/space'
                ],
                [
                    'name'  => __('All'),
                    'class' => 'active'
                ],
            ],
            'page_title'         => __("Manage Spaces"),
        ];
        return view('Space::frontend.manageSpace.index', $data);
    }

    public function createSpace(Request $request)
    {
        $this->checkPermission('tour_create');
        $row = new $this->space();
        $data = [
            'row'           => $row,
            'translation' => new TourTranslation(),
            'space_location' => Location::get()->toTree(),
            'attributes'    => Attributes::where('service', 'space')->get(),
            'breadcrumbs'        => [
                [
                    'name' => __('Manage Spaces'),
                    'url'  => 'user/space'
                ],
                [
                    'name'  => __('Create'),
                    'class' => 'active'
                ],
            ],
            'page_title'         => __("Create Spaces"),
        ];
        return view('Space::frontend.manageSpace.detail', $data);
    }


    public function store( Request $request, $id ){

        if($id>0){
            $this->checkPermission('space_update');
            $row = $this->space::find($id);
            if (empty($row)) {
                return redirect(route('space.vendor.index'));
            }

            if($row->create_user != Auth::id() and !$this->hasPermission('space_manage_others'))
            {
                return redirect(route('space.vendor.index'));
            }
        }else{
            $this->checkPermission('space_create');
            $row = new $this->space();
            $row->status = "draft";
        }
        $dataKeys = [
            'title',
            'content',
            'price',
            'is_instant',
            'video',
            'faqs',
            'image_id',
            'banner_image_id',
            'gallery',
            'bed',
            'bathroom',
            'square',
            'location_id',
            'address',
            'map_lat',
            'map_lng',
            'map_zoom',
            'price',
            'sale_price',
            'max_guests',
            'enable_extra_price',
            'extra_price',
            'is_featured',
            'default_state'
        ];
        if($this->hasPermission('space_manage_others')){
            $dataKeys[] = 'create_user';
        }

        $row->fillByAttr($dataKeys,$request->input());

        $res = $row->saveOriginOrTranslation($request->input('lang'),true);

        if ($res) {
            if(!$request->input('lang') or is_default_lang($request->input('lang'))) {
                $this->saveTerms($row, $request);
            }

            if($id > 0 ){
                return back()->with('success',  __('Space updated') );
            }else{
                return redirect(route('space.vendor.edit',['id'=>$row->id]))->with('success', __('Space created') );
            }
        }
    }

    public function saveTerms($row, $request)
    {
        if (empty($request->input('terms'))) {
            $this->space_term::where('target_id', $row->id)->delete();
        } else {
            $term_ids = $request->input('terms');
            foreach ($term_ids as $term_id) {
                $this->space_term::firstOrCreate([
                    'term_id' => $term_id,
                    'target_id' => $row->id
                ]);
            }
            $this->space_term::where('target_id', $row->id)->whereNotIn('term_id', $term_ids)->delete();
        }
    }

    public function editSpace(Request $request, $id)
    {
        $this->checkPermission('space_update');
        $user_id = Auth::id();
        $row = $this->space::where("create_user", $user_id);
        $row = $row->find($id);
        if (empty($row)) {
            return redirect(route('space.vendor.index'))->with('warning', __('Space not found!'));
        }
        $translation = $row->translateOrOrigin($request->query('lang'));
        $data = [
            'translation'    => $translation,
            'row'           => $row,
            'space_location' => Location::get()->toTree(),
            'attributes'    => Attributes::where('service', 'space')->get(),
            "selected_terms" => $row->terms->pluck('term_id'),
            'breadcrumbs'        => [
                [
                    'name' => __('Manage Spaces'),
                    'url'  => 'user/space'
                ],
                [
                    'name'  => __('Edit'),
                    'class' => 'active'
                ],
            ],
            'page_title'         => __("Edit Spaces"),
        ];
        return view('Space::frontend.manageSpace.detail', $data);
    }

    public function deleteSpace($id)
    {
        $this->checkPermission('space_delete');
        $user_id = Auth::id();
        $this->space::where("create_user", $user_id)->where("id", $id)->first()->delete();
        return redirect(route('space.vendor.list'))->with('success', __('Delete space success!'));
    }

    public function bookingReport(Request $request)
    {
        $data = [
            'bookings' => Booking::getBookingHistory($request->input('status'), false , Auth::id() , 'space'),
            'statues'  => config('booking.statuses'),
            'breadcrumbs'        => [
                [
                    'name' => __('Booking Report'),
                    'class'  => 'active'
                ]
            ],
            'page_title'         => __("Booking Report"),
        ];
        return view('Space::frontend.manageSpace.bookingReport', $data);
    }
}