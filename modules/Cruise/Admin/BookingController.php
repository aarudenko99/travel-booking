<?php
namespace Modules\Cruise\Admin;

use Illuminate\Http\Request;
use Modules\AdminController;
use Modules\Cruise\Models\Cruise;
use Modules\Cruise\Models\CruiseCategory;

class BookingController extends AdminController
{
    public function __construct()
    {
        $this->setActiveMenu('admin/module/cruise');
        parent::__construct();
    }

    public function index(Request $request){

        $this->checkPermission('cruise_create');

        $q = Cruise::query();

        if($request->query('s')){
            $q->where('title','like','%'.$request->query('s').'%');
        }

        if ($cat_id = $request->query('cat_id')) {
            $cat = CruiseCategory::find($cat_id);
            if(!empty($cat)) {
                $q->join('bravo_cruise_category', function ($join) use ($cat) {
                    $join->on('bravo_cruise_category.id', '=', 'bravo_cruises.category_id')
                        ->where('bravo_cruise_category._lft','>=',$cat->_lft)
                        ->where('bravo_cruise_category._rgt','>=',$cat->_lft);
                });
            }
        }

        if(!$this->hasPermission('cruise_manage_others')){
            $q->where('create_user',$this->currentUser()->id);
        }

        $q->orderBy('bravo_cruises.id','desc');

        $rows = $q->paginate(10);

        $current_month = strtotime(date('Y-m-01',time()));

        if($request->query('month')){
            $date = date_create_from_format('m-Y',$request->query('month'));
            if(!$date){
                $current_month = time();
            }else{
                $current_month = $date->getTimestamp();
            }
        }

        $prev_url = url('admin/module/cruise/booking/').'?'.http_build_query(array_merge($request->query(),[
           'month'=> date('m-Y',$current_month - MONTH_IN_SECONDS)
        ]));
        $next_url = url('admin/module/cruise/booking/').'?'.http_build_query(array_merge($request->query(),[
           'month'=> date('m-Y',$current_month + MONTH_IN_SECONDS)
        ]));

        $cruise_categories = CruiseCategory::where('status', 'publish')->get()->toTree();
        $breadcrumbs = [
            [
                'name' => __('Cruises'),
                'url'  => 'admin/module/cruise'
            ],
            [
                'name'  => __('Booking'),
                'class' => 'active'
            ],
        ];
        $page_title = __('Cruise Booking History');
        return view('Cruise::admin.booking.index',compact('rows','cruise_categories','breadcrumbs','current_month','page_title','request','prev_url','next_url'));
    }
    public function test(){
        $d = new \DateTime('2019-07-04 00:00:00');

        $d->modify('+ 4 hours');
        echo $d->format('Y-m-d H:i:s');
    }
}