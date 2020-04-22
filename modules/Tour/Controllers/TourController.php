<?php

namespace Modules\Tour\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Modules\Core\Models\Attributes;
use Modules\Location\Models\Location;
use Modules\Review\Models\Review;
use Modules\Tour\Models\Tour;
use Modules\Tour\Models\TourCategory;

//    use Modules\Vendor\Models\VendorPlan;

class TourController extends Controller {
	protected $tour;

	public function __construct() {
		$this->tour = Tour::class;
	}

	public function index(Request $request) {

		$is_ajax = $request->query('_ajax');
		$model_Tour = $this->tour::select("bravo_tours.*");
		$model_Tour->where("bravo_tours.status", "publish");
		if (!empty($location_id = $request->query('location_id'))) {
			$location = Location::where('id', $location_id)->where("status", "publish")->first();
			if (!empty($location)) {
				$model_Tour->join('bravo_locations', function ($join) use ($location) {
					$join->on('bravo_locations.id', '=', 'bravo_tours.location_id')
						->where('bravo_locations._lft', '>=', $location->_lft)
						->where('bravo_locations._rgt', '<=', $location->_rgt);
				});
			}
		}

		if (!empty($price_range = $request->query('price_range'))) {
			$pri_from = explode(";", $price_range)[0];
			$pri_to = explode(";", $price_range)[1];
			$raw_sql_min_max = "( (bravo_tours.sale_price > 0 and bravo_tours.sale_price >= ? ) OR (bravo_tours.sale_price <= 0 and bravo_tours.price >= ?) )
								AND ( (bravo_tours.sale_price > 0 and bravo_tours.sale_price <= ? ) OR (bravo_tours.sale_price <= 0 and bravo_tours.price <= ?) )";
			$model_Tour->WhereRaw($raw_sql_min_max, [$pri_from, $pri_from, $pri_to, $pri_to]);
		}
		if (!empty($category_ids = $request->query('cat_id'))) {
			if (!is_array($category_ids)) {
				$category_ids = [$category_ids];
			}

			$list_cat = TourCategory::whereIn('id', $category_ids)->where("status", "publish")->get();
			if (!empty($list_cat)) {
				$where_left_right = [];
				foreach ($list_cat as $cat) {
					$where_left_right[] = " ( bravo_tour_category._lft >= {$cat->_lft} AND bravo_tour_category._rgt <= {$cat->_rgt} ) ";

				}
			}

		}
		$terms = $request->query('terms');
		if (is_array($terms) && !empty($terms)) {
			$model_Tour->join('bravo_tour_term as tt', 'tt.tour_id', "bravo_tours.id")->whereIn('tt.term_id', $terms);
		}
		$model_Tour->orderBy("id", "desc");
		$model_Tour->groupBy("bravo_tours.id");

		/*$model_Tour->join('users', function ($joinUser) {
			                $joinUser->on('bravo_tours.create_user', '=', 'users.id')
			                    ->where('users.status', 'publish')
			                    ->where('users.vendor_plan_enable', 1)
			                    ->orWhere('bravo_tours.create_user', '1');
			            });
			            $model_Tour->join('core_vendor_plans', function ($joinPlan) {
			                $joinPlan->on('users.vendor_plan_id', '=', 'core_vendor_plans.id')
			                    ->where(function ($query2) {
			                        $query2->where('core_vendor_plans.status', 'publish');
			                    });
		*/
		$list = $model_Tour->with(['location', 'hasWishList'])->paginate(9);
		// foreach ($list->reverse() as $key) {
		// 	echo($key->id.'\n');
		// }
		// exit();
		$markers = [];
		if (!empty($list)) {
			foreach ($list->reverse() as $row) {
				echo($row->id);
				$markers[] = [
					"id" => $row->id,
					"title" => $row->title,
					"lat" => (float) $row->map_lat,
					"lng" => (float) $row->map_lng,
					"gallery" => $row->getGallery(true),
					"infobox" => view('Tour::frontend.layouts.search.loop-gird', ['row' => $row, 'disable_lazyload' => 1, 'wrap_class' => 'infobox-item'])->render(),
					'marker' => url('images/icons/png/pin.png'),
					//                    'marker'=>'http://travelhotel.wpengine.com/wp-content/uploads/2018/11/ico_mapker_hotel.png'
				];
			}
		}
		$limit_location = 15;
		if (empty(setting_item("space_location_search_style")) or setting_item("space_location_search_style") == "normal") {
			$limit_location = 1000;
		}
		$data = [
			'rows' => $list,
			'tour_category' => TourCategory::where('status', 'publish')->get()->toTree(),
			'tour_location' => Location::where('status', 'publish')->limit($limit_location)->get()->toTree(),
			'tour_min_max_price' => Tour::getMinMaxPrice(),
			'markers' => $markers,
			"blank" => 1,
			"seo_meta" => Tour::getSeoMetaForPageList(),
		];
		$layout = setting_item("tour_layout_search", 'normal');
		if ($request->query('_layout')) {
			$layout = $request->query('_layout');
		}
		if ($is_ajax) {
			$this->sendSuccess([
				'html' => view('Tour::frontend.layouts.search-map.list-item', $data)->render(),
				"markers" => $data['markers'],
			]);
		}
		$data['attributes'] = Attributes::where('service', 'tour')->get();

		if ($layout == "map") {
			$data['body_class'] = 'has-search-map';
			$data['html_class'] = 'full-page';
			return view('Tour::frontend.search-map', $data);
		}
		return view('Tour::frontend.search', $data);
	}

	public function detail(Request $request, $slug) {
		$row = $this->tour::where('slug', $slug)->where("status", "publish")->first();
		if (empty($row)) {
			return redirect('/');
		}

		//Auth::user()->can('viewAny', Tour::class);

		$translation = $row->translateOrOrigin(app()->getLocale());
		$tour_related = [];
		$location_id = $row->location_id;
		if (!empty($location_id)) {
			$tour_related = $this->tour::where('location_id', $location_id)->take(4)->whereNotIn('id', [$row->id])->get();
		}
		$time_slots = [];
		$time = strtotime('2019-01-01 00:00:00');
		for ($k = 0; $k <= 23; $k++):
			$val = date('H:i', $time + 60 * 60 * $k);
			$time_slots[] = $val;
		endfor;
		$review_list = Review::where('object_id', $row->id)
			->where('object_model', 'tour')
			->where("status", "approved")
			->orderBy("id", "desc")
			->with('author')
			->paginate(setting_item('tour_review_number_per_page', 5));
		//////////////////////////////////////////
		$user = \Auth::user();
		$vendor = "";
		$admin = "";
		$agent = "";
		if ($user) {
			$user_role = $user->getRoleNames();
			$vendor = in_array('vendor', $user_role->toArray());
			$admin = in_array('administrator', $user_role->toArray());
			$agent = in_array('agent', $user_role->toArray());
		}

		$pre_id = $request->input('agent_id');
		$is_user = User::find($pre_id);

		if ($is_user) {
			$user_role = $is_user->getRoleNames();
			// dd($user_role);
			$is_agent = in_array('agent', $user_role->toArray());
			if ($is_agent) {
				$pre_agent_id = $pre_id;
			} else{
				$pre_agent_id = "";
			}
		} else {
			$pre_agent_id = "";
		}
		//////////////////////////////////////////////
		$reviews = 20;
		$data = [
			'row' => $row,
			'reviews' => $reviews,
			'translation' => $translation,
			'tour_related' => $tour_related,
			'time_slots' => $time_slots,
			'booking_data' => $row->getBookingData(),
			'review_list' => $review_list,
			'seo_meta' => $row->getSeoMetaWithTranslation(app()->getLocale(), $translation),
			'body_class' => 'is_single',
			'vendor' => $vendor, ///////////////////////////////
			'admin' => $admin, /////////////////////////////////
			'agent' => $agent, /////////////////////////////////
			'pre_agent_id' => $pre_agent_id,
		];
		$this->setActiveMenu($row);
		// exit($row->getScoreReview()['total_review']);
		return view('Tour::frontend.detail', $data);
	}
}
