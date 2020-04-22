<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 8/2/2019
 * Time: 9:43 AM
 */
namespace Modules\Cruise\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Booking\Models\Booking;
use Modules\FrontendController;
use Modules\Cruise\Models\Cruise;
use Modules\Cruise\Models\CruiseDate;

class AvailabilityController extends FrontendController{

    protected $cruiseClass;
    /**
     * @var CruiseDate
     */
    protected $cruiseDateClass;
    /**
     * @var Booking
     */
    protected $bookingClass;

    protected $indexView = 'Cruise::frontend.user.availability';

    public function __construct()
    {
        parent::__construct();
        $this->cruiseClass = Cruise::class;
        $this->cruiseDateClass = CruiseDate::class;
        $this->bookingClass = Booking::class;
    }

    public function index(Request $request){
        $this->checkPermission('cruise_create');

        $q = $this->cruiseClass::query();

        if($request->query('s')){
            $q->where('title','like','%'.$request->query('s').'%');
        }

        if(!$this->hasPermission('cruise_manage_others')){
            $q->where('create_user',$this->currentUser()->id);
        }

        $q->orderBy('bravo_cruises.id','desc');

        $rows = $q->paginate(15);

        $current_month = strtotime(date('Y-m-01',time()));

        if($request->query('month')){
            $date = date_create_from_format('m-Y',$request->query('month'));
            if(!$date){
                $current_month = time();
            }else{
                $current_month = $date->getTimestamp();
            }
        }
        $breadcrumbs = [
            [
                'name' => __('Cruises'),
                'url'  => 'admin/module/cruise'
            ],
            [
                'name'  => __('Availability'),
                'class' => 'active'
            ],
        ];
        $page_title = __('Cruises Availability');

        return view($this->indexView,compact('rows','breadcrumbs','current_month','page_title','request'));
    }

    public function loadDates(Request $request){

        $request->validate([
            'id'=>'required',
            'start'=>'required',
            'end'=>'required',
        ]);

        $cruise = $this->cruiseClass::find($request->query('id'));
        if(empty($cruise)){
            $this->sendError(__('Cruise not found'));
        }

        $query = $this->cruiseDateClass::query();
        $query->where('target_id',$request->query('id'));
        $query->where('start_date','>=',date('Y-m-d H:i:s',strtotime($request->query('start'))));
        $query->where('end_date','<=',date('Y-m-d H:i:s',strtotime($request->query('end'))));
        $rows =  $query->take(40)->get();
        $allDates = [];
        for($i = strtotime($request->query('start')); $i <= strtotime($request->query('end')); $i+= DAY_IN_SECONDS)
        {
            $date = [
                'id'=>rand(0,999),
                'active'=>0,
                'price'=>(!empty($cruise->sale_price) and $cruise->sale_price > 0 and $cruise->sale_price < $cruise->price) ? $cruise->sale_price : $cruise->price,
                'is_default'=>true,
                'textColor'=>'#2791fe'
            ];
            $date['price_html'] = format_money($date['price']);
            $date['max_guests'] = $cruise->max_people;
            $date['title'] = $date['event']  = $date['price_html'];
            $date['start'] = $date['end'] = date('Y-m-d',$i);
            if($cruise->default_state){
                $date['active'] = 1;
            }else{
                $date['title'] = $date['event'] = __('Blocked');
                $date['backgroundColor'] = 'orange';
                $date['borderColor'] = '#fe2727';
                $date['classNames'] = ['blocked-event'];
                $date['textColor'] = '#fe2727';
            }
            
            if($request->input('for_single')){
                if(empty($cruise->max_people) and $cruise->max_people < 1){
                    $date['active'] = 0;
                }
            }

            if(!empty($cruise->meta->enable_person_types) and $cruise->meta->enable_person_types==1){
                $date['person_types'] = $cruise->meta->person_types ;
                if(!empty( $date['person_types'] )){
                    $c_title = "";
                    foreach (  $date['person_types']  as &$person){
                        $c_title .= $person['name'].": ".format_money($person['price'])."<br>";
                        //for single
                        $person['display_price'] = format_money($person['price']);
                        $person['number'] = $person['min'] ?? 0;
                    }
                    $date['title'] = $date['event']  = $c_title;
                }
            }

            // Open Hours
            if (!empty($cruise->meta->enable_open_hours) and $cruise->meta->enable_open_hours == 1) {
                $open_hours = $cruise->meta->open_hours;
                $nDate = date('N', $i);
                if (!isset($open_hours[$nDate]) or empty($open_hours[$nDate]['enable'])) {
                    $date['active'] = 0;
                }
            }

            $allDates[date('Y-m-d',$i)] = $date;
        }
        if(!empty($rows))
        {
            foreach ($rows as $row)
            {
                $row->start = date('Y-m-d',strtotime($row->start_date));
                $row->end = date('Y-m-d',strtotime($row->start_date));
                $row->textColor = '#2791fe';
                $row->title = $row->event = format_money($row->price);

                if($request->input('for_single')){
                    if(empty($row->max_guests) and $row->max_guests < 1 ){
                        $row->active = 0;
                    }
                }

                if(!$row->active)
                {
                    $row->title = $row->event = __('Blocked');
                    $row->backgroundColor = '#fe2727';
                    $row->classNames = ['blocked-event'];
                    $row->textColor = '#fe2727';
                }else{
                    $row->classNames = ['active-event'];
                    $list_person_types = null;
                    if(!empty($cruise->meta->enable_person_types) and $cruise->meta->enable_person_types==1){
                        $list_person_types = $row->person_types;
                        if(!empty($list_person_types)){
                            $c_title = "";
                            foreach ($list_person_types as &$person){
                                $c_title .= $person['name'].": ".format_money($person['price'])."<br>";
                                //for single
                                $person['display_price'] = format_money($person['price']);
                                $person['number'] = $person['min'] ?? 0;
                            }
                            $row->title  = $c_title;
                        }
                    }
                    $row->person_types = $list_person_types;
                    // Open Hours
                    if (!empty($cruise->meta->enable_open_hours) and $cruise->meta->enable_open_hours == 1) {
                        $open_hours = $cruise->meta->open_hours;
                        $nDate = date('N', strtotime($row->start_date));
                        if (!isset($open_hours[$nDate]) or empty($open_hours[$nDate]['enable'])) {
                            $row->active = 0;
                        }
                    }

                }
                $allDates[date('Y-m-d',strtotime($row->start_date))] = $row;
            }
        }

        if($request->input('for_single'))
        {
            $bookings = $this->bookingClass::getBookingInRanges($cruise->id,$cruise->type,$request->query('start'),$request->query('end'));
            if(!empty($bookings))
            {
                foreach ($bookings as $booking){
                    for($i = strtotime($booking->start_date); $i <= strtotime($booking->end_date); $i+= DAY_IN_SECONDS){
                        if(isset($allDates[date('Y-m-d',$i)])){
                            $total_guests_booking = $booking->total_guests;
                            $max_guests = $allDates[date('Y-m-d',$i)]['max_guests'];
                            if($total_guests_booking >= $max_guests){
                                $allDates[date('Y-m-d',$i)]['active'] = 0;
                            }
                        }
                    }
                }
            }
        }

        $data = array_values($allDates);

        return response()->json($data);
    }

    public function store(Request $request){

        $request->validate([
            'target_id'=>'required',
            'start_date'=>'required',
            'end_date'=>'required'
        ]);

        $cruise = $this->cruiseClass::find($request->input('target_id'));
        $target_id = $request->input('target_id');

        if(empty($cruise)){
            $this->sendError(__('Cruise not found'));
        }

        if(!$this->hasPermission('cruise_manage_others')){
            if($cruise->create_user != Auth::id()){
                $this->sendError("You do not have permission to access it");
            }
        }

        $postData = $request->input();
        for($i = strtotime($request->input('start_date')); $i <= strtotime($request->input('end_date')); $i+= DAY_IN_SECONDS)
        {
            $date = CruiseDate::where('start_date',date('Y-m-d',$i))->where('target_id',$target_id)->first();
            if(empty($date)){
                $date = new CruiseDate();
                $date->target_id = $target_id;
            }
            $postData['start_date'] = date('Y-m-d H:i:s',$i);
            $postData['end_date'] = date('Y-m-d H:i:s',$i);
            $date->fillByAttr([
                'start_date','end_date','price',
                'max_guests',
                'active',
                'person_types'
            ],$postData);
            $date->save();
        }
        $this->sendSuccess([],__("Update Success"));
    }
}
