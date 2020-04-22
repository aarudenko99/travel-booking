<?php

    namespace Modules\Cruise\Models;

    use App\Currency;
    use App\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Modules\Booking\Models\Bookable;
    use Modules\Booking\Models\Booking;
    use Modules\Location\Models\Location;
    use Modules\Review\Models\Review;
    use Modules\Cruise\Models\CruiseTerm;
    use Modules\Media\Helpers\FileHelper;
    use Illuminate\Support\Facades\Cache;
    use Validator;
    use Illuminate\Database\Eloquent\SoftDeletes;
    use Modules\Core\Models\SEO;
    use Modules\User\Models\UserWishList;

    class Cruise extends Bookable
    {
        use SoftDeletes;
        protected $table                              = 'bravo_cruises';
        public    $checkout_booking_detail_file       = 'Cruise::frontend/booking/detail';
        public    $checkout_booking_detail_modal_file = 'Cruise::frontend/booking/detail-modal';
        public    $email_new_booking_file             = 'Cruise::emails.new_booking_detail';
        public    $type                               = 'cruise';
        protected $fillable                           = [
            //Cruise info
            'title',
            'slug',
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
            'is_featured',
            'gallery',
            'video',
            //Price
            'price',
            'sale_price',
            //Cruise type
            'duration',
            'max_people',
            'min_people',
            //Extra Info
            'faqs',
            'status'
        ];
        protected $slugField                          = 'slug';
        protected $slugFromField                      = 'title';
        protected $seo_type                           = 'cruise';
        /**
         * The attributes that should be casted to native types.
         *
         * @var array
         */
        protected $casts = [
            'faqs' => 'array',
        ];

        public static function getModelName()
        {
            return __("Cruise");
        }

        public static function getTableName()
        {
            return with(new static)->table;
        }

        protected $bookingClass;
        protected $cruiseTermClass;
        protected $cruiseTranslationClass;
        protected $cruiseMetaClass;
        protected $userWishListClass;
        protected $reviewClass;

        public function __construct(array $attributes = [])
        {
            parent::__construct($attributes);
            $this->bookingClass = Booking::class;
            $this->cruiseTermClass = CruiseTerm::class;
            $this->cruiseTranslationClass = CruiseTranslation::class;
            $this->cruiseMetaClass = CruiseMeta::class;
            $this->userWishListClass = UserWishList::class;
            $this->reviewClass = Review::class;
        }

        /**
         * Get Category
         *
         * @return \Illuminate\Database\Eloquent\Relations\HasOne
         */
        public function category_cruise()
        {
            return $this->hasOne("Modules\Cruise\Models\CruiseCategory", "id", 'category_id');
        }

        /**
         * Get SEO fop page list
         *
         * @return mixed
         */
        static public function getSeoMetaForPageList()
        {
            $meta['seo_title'] = __("Search for Cruises");
            if (!empty($title = setting_item_with_lang("cruise_page_list_seo_title", false))) {
                $meta['seo_title'] = $title;
            } else if (!empty($title = setting_item_with_lang("cruise_page_search_title"))) {
                $meta['seo_title'] = $title;
            }
            $meta['seo_image'] = null;
            if (!empty($title = setting_item("cruise_page_list_seo_image"))) {
                $meta['seo_image'] = $title;
            } else if (!empty($title = setting_item("cruise_page_search_banner"))) {
                $meta['seo_image'] = $title;
            }
            $meta['seo_desc'] = setting_item_with_lang("cruise_page_list_seo_desc");
            $meta['seo_share'] = setting_item_with_lang("cruise_page_list_seo_share");
            $meta['full_url'] = url(config('cruise.cruise_route_prefix'));
            return $meta;
        }

        /**
         * Get Category
         *
         * @return \Illuminate\Database\Eloquent\Relations\HasOne
         */
        public function meta()
        {
            return $this->hasOne("Modules\Cruise\Models\CruiseMeta", "cruise_id");
        }

        /**
         * Get Category
         *
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function cruise_term()
        {
            return $this->hasMany($this->cruiseTermClass, "cruise_id");
        }

        public function getDetailUrl($locale = false)
        {
            return url(app_get_locale(false, false, '/') . config('cruise.cruise_route_prefix') . "/" . $this->slug);
        }

        public static function getLinkForPageSearch($locale = false, $param = [])
        {

            return url(app_get_locale(false, false, '/') . config('cruise.cruise_route_prefix') . "?" . http_build_query($param));
        }


        public function getGallery($featuredIncluded = false)
        {
            if (empty($this->gallery))
                return $this->gallery;
            $list_item = [];
            if ($featuredIncluded and $this->image_id) {
                $list_item[] = [
                    'large' => FileHelper::url($this->image_id, 'full'),
                    'thumb' => FileHelper::url($this->image_id, 'thumb')
                ];
            }
            $items = explode(",", $this->gallery);
            foreach ($items as $k => $item) {
                $large = FileHelper::url($item, 'full');
                $thumb = FileHelper::url($item, 'thumb');
                $list_item[] = [
                    'large' => $large,
                    'thumb' => $thumb
                ];
            }
            return $list_item;
        }

        public function getEditUrl()
        {
            return url('admin/module/cruise/edit/' . $this->id);
        }

        public function getDiscountPercentAttribute()
        {
            if (!empty($this->price) and $this->price > 0
                and !empty($this->sale_price) and $this->sale_price > 0
                and $this->price > $this->sale_price
            ) {
                $percent = 100 - ceil($this->sale_price / ($this->price / 100));
                return $percent . "%";
            }
        }

        function getDatefomat($value)
        {
            return \Carbon\Carbon::parse($value)->format('j F, Y');
        }

        public function saveMeta(\Illuminate\Http\Request $request)
        {
            $meta = CruiseMeta::where('cruise_id', $this->id)->first();
            if (!$meta) {
                $meta = new CruiseMeta();
                $meta->cruise_id = $this->id;
            }
            $meta->fill($request->input());
            return $meta->save();
        }

        public function fill(array $attributes)
        {
            if (!empty($attributes)) {
                foreach ($this->fillable as $item) {
                    $attributes[$item] = $attributes[$item] ?? null;
                }
            }
            return parent::fill($attributes); // TODO: Change the autogenerated stub
        }

        public function isBookable()
        {
            if ($this->status != 'publish')
                return false;
            return parent::isBookable();
        }

        public function addToCart(Request $request)
        {
            $this->addToCartValidate($request);
            // Add Booking
            $total = 0;
            $total_guests = 0;
            $discount = 0;
            $price = $this->sale_price ? $this->sale_price : $this->price;
            $extra_price = [];
            $extra_price_input = $request->input('extra_price');
            $person_types = [];
            $person_types_input = $request->input('person_types');
            $discount_by_people = [];
            $meta = $this->meta;
            if ($meta) {
                if ($meta->enable_person_types and !empty($meta->person_types)) {
                    if (!empty($meta->person_types)) {
                        foreach ($meta->person_types as $k => $type) {

                            if (isset($person_types_input[$k]) and $person_types_input[$k]['number']) {
                                $type['number'] = $person_types_input[$k]['number'];
                                $person_types[] = $type;
                                $total += $type['price'] * $type['number'];
                                $total_guests += $type['number'];
                            }
                        }
                    }
                } else {

                    $total += $price * $request->input('guests');
                    $total_guests += $request->input('guests');
                }
                if ($meta->enable_extra_price and !empty($meta->extra_price)) {
                    if (!empty($meta->extra_price)) {
                        foreach ($meta->extra_price as $k => $type) {
                            if (isset($extra_price_input[$k]) and $extra_price_input[$k]['enable']) {

                                $type_total = 0;

                                switch ($type['type']) {
                                    case "one_time":
                                        $type_total = $type['price'];
                                        break;
                                    case "per_hour":
                                        $type_total = $type['price'] * $this->duration;
                                        break;
                                    case "per_day":
                                        $type_total = $type['price'] * ceil($this->duration / 24);
                                        break;
                                }
                                if (!empty($type['per_person'])) {
                                    $type_total *= $total_guests;
                                }
                                $type['total'] = $type_total;
                                $total += $type_total;
                                $extra_price[] = $type;
                            }
                        }
                    }
                }
                if ($meta->discount_by_people and !empty($meta->discount_by_people)) {
                    foreach ($meta->discount_by_people as $type) {
                        if ($type['from'] <= $total_guests and (!$type['to'] or $type['to'] >= $total_guests)) {

                            $type_total = 0;

                            switch ($type['type']) {
                                case "fixed":
                                    $type_total = $type['amount'];
                                    break;
                                case "percent":
                                    $type_total = $total / 100 * $type['amount'];
                                    break;
                            }
                            $total -= $type_total;
                            $discount += $type_total;
                            $type['total'] = $type_total;
                            $discount_by_people[] = $type;
                        }
                    }
                }
            } else {
                // Default
                $total += $price * $request->input('guests');
                $total_guests += $request->input('guests');
            }
            $start_date = new \DateTime($request->input('start_date'));
            if (empty($start_date)) {
                $this->sendError(__("Start date is not a valid date"));
            }

            //Buyer Fees
            $total_before_fees = $total;
            $list_fees = setting_item('cruise_booking_buyer_fees');
            if (!empty($list_fees)) {
                $lists = json_decode($list_fees, true);
                foreach ($lists as $item) {
                    if (!empty($item['per_person']) and $item['per_person'] == "on") {
                        $total += $item['price'] * $total_guests;
                    } else {
                        $total += $item['price'];
                    }
                }
            }

            $booking = new Booking();
            $booking->status = 'draft';
            $booking->object_id = $request->input('service_id');
            $booking->object_model = $request->input('service_type');
            $booking->vendor_id = $this->create_user;
            $booking->customer_id = Auth::id();
            $booking->total = $total_before_fees;
            $booking->total_guests = $total_guests;
            $booking->start_date = $start_date->format('Y-m-d H:i:s');
            $start_date->modify('+ ' . max(1, $this->duration) . ' hours');
            $booking->end_date = $start_date->format('Y-m-d H:i:s');
            $booking->buyer_fees = $list_fees ?? '';
            $booking->total_before_fees = $total_before_fees;

            $booking->calculateCommission();

            $check = $booking->save();
            if ($check) {

                $this->bookingClass::clearDraftBookings();

                $booking->addMeta('duration', $this->duration);
                $booking->addMeta('base_price', $price);
                $booking->addMeta('guests', max($total_guests, $request->input('guests')));
                $booking->addMeta('extra_price', $extra_price);
                $booking->addMeta('person_types', $person_types);
                $booking->addMeta('discount_by_people', $discount_by_people);
                $this->sendSuccess([
                    'url' => $booking->getCheckoutUrl()
                ]);
            }
            $this->sendError(__("Can not check availability"));
        }

        public function beforeCheckout(Request $request, $booking)
        {
            $maxGuests = $this->getNumberAvailableBooking($booking->start_date);
            if ($booking->total_guests > $maxGuests) {
                $this->sendError(__("There are " . $maxGuests . " guests available for your selected date"));
            }
        }

        public function getNumberAvailableBooking($start_date)
        {
            $cruiseDate = CruiseDate::where('target_id', $this->id)->where('start_date', $start_date)->where('active', 1)->first();
            $totalGuests = Booking::where('object_id', $this->id)->where('start_date', $start_date)->whereNotIn('status', Booking::$notAcceptedStatus)->sum('total_guests');
            $maxGuests = !empty($cruiseDate->max_guests) ? $cruiseDate->max_guests : $this->max_people;
            $number = $maxGuests - $totalGuests;
            return $number > 0 ? $number : 0;
        }

        public function addToCartValidate(Request $request)
        {
            $meta = $this->meta;
            $rules = [
                'guests'     => 'required|integer|min:1',
                'start_date' => 'required|date_format:Y-m-d'
            ];
            $start_date = $request->input('start_date');
            if ($meta) {

                // Percent Types
                if ($meta->enable_person_types) {
                    unset($rules['guests']);
                    $rules['person_types'] = 'required';
                    $person_types_configs = $meta->person_types;
                    if (!empty($person_types_configs) and is_array($person_types_configs)) {
                        foreach ($person_types_configs as $k => $person_type) {
                            $ruleStr = 'integer';
                            if ($person_type['min']) {
                                $ruleStr .= '|min:' . $person_type['min'];
                            }
                            if ($person_type['max']) {
                                $ruleStr .= '|max:' . $person_type['max'];
                            }
                            if ($ruleStr) {
                                $rules['person_types.' . $k . '.number'] = $ruleStr;
                            }
                        }
                    }
                }
            }

            // Validation
            if (!empty($rules)) {
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $this->sendError('', ['errors' => $validator->errors()]);
                }
            }
            if ($meta) {
                // Open Hours
                if ($meta->enable_open_hours) {

                    $open_hours = $meta->open_hours;
                    $nDate = date('N', strtotime($start_date));
                    if (!isset($open_hours[$nDate]) or empty($open_hours[$nDate]['enable'])) {
                        $this->sendError(__("This cruise is not open on your selected day"));
                    }
                }
            }

            if (!empty($request->person_types)) {
                $totalGuests = array_sum(array_pluck($request->person_types, 'number')) ?? 0;
            } else {
                $totalGuests = $request->guests;
            }

            $numberGuestsCanBook = $this->getNumberAvailableBooking($start_date);

            if ($totalGuests > $numberGuestsCanBook) {
                $this->sendError(__("There are " . $numberGuestsCanBook . " guests available for your selected date"));
            }
            return true;
        }

        public function getBookingData()
        {
            $booking_data = [
                'id'           => $this->id,
                'person_types' => [],
                'max'          => 0,
                'open_hours'   => [],
                'extra_price'  => [],
                'minDate'      => date('m/d/Y'),
                'duration'     => $this->duration,
            ];
            $meta = $this->meta ?? false;
            $lang = app()->getLocale();
            if ($meta) {
                if ($meta->enable_person_types) {
                    $booking_data['person_types'] = $meta->person_types;
                    foreach ($booking_data['person_types'] as $k => &$type) {
                        if (!empty($lang) and !empty($type['name_' . $lang])) {
                            $type['name'] = $type['name_' . $lang];
                            $type['desc'] = $type['desc_' . $lang];
                        }
                        $type['min'] = (int)$type['min'];
                        $type['max'] = (int)$type['max'];
                        $type['number'] = $type['min'];
                        $type['display_price'] = format_money($type['price']);
                    }

                    $booking_data['person_types'] = array_values((array)$booking_data['person_types']);
                }
                if ($meta->enable_extra_price) {
                    $booking_data['extra_price'] = $meta->extra_price;
                    if (!empty($booking_data['extra_price'])) {
                        foreach ($booking_data['extra_price'] as $k => &$type) {
                            if (!empty($lang) and !empty($type['name_' . $lang])) {
                                $type['name'] = $type['name_' . $lang];
                            }
                            $type['number'] = 0;
                            $type['enable'] = 0;
                            $type['price_html'] = format_money($type['price']);
                            $type['price_type'] = '';
                            switch ($type['type']) {
                                case "per_day":
                                    $type['price_type'] .= '/' . __('day');
                                    break;
                                case "per_hour":
                                    $type['price_type'] .= '/' . __('hour');
                                    break;
                            }
                            if (!empty($type['per_person'])) {
                                $type['price_type'] .= '/' . __('guest');
                            }
                        }
                    }

                    $booking_data['extra_price'] = array_values((array)$booking_data['extra_price']);
                }
                if ($meta->enable_open_hours) {
                    $booking_data['open_hours'] = $meta->open_hours;
                }
            }
            return $booking_data;
        }

        public static function searchForMenu($q = false)
        {
            $query = static::select('id', 'title as name');
            if (strlen($q)) {

                $query->where('title', 'like', "%" . $q . "%");
            }
            $a = $query->limit(10)->get();
            return $a;
        }

        public static function getMinMaxPrice()
        {
            $model = parent::selectRaw('MIN( CASE WHEN sale_price > 0 THEN sale_price ELSE ( price ) END ) AS min_price ,
                                    MAX( CASE WHEN sale_price > 0 THEN sale_price ELSE ( price ) END ) AS max_price ')->where("status", "publish")->first();
            if (empty($model->min_price) and empty($model->max_price)) {
                return [
                    0,
                    100
                ];
            }
            return [
                $model->min_price,
                $model->max_price
            ];
        }

        public function getReviewEnable()
        {
            return setting_item("cruise_enable_review", 0);
        }

        public function getReviewApproved()
        {
            return setting_item("cruise_review_approved", 0);
        }

    public function check_enable_review_after_booking()
    {
        $option = setting_item("cruise_enable_review_after_booking", 0);
        if ($option) {
            $number_review = $this->reviewClass::countReviewByServiceID($this->id, Auth::id(),false,$this->type) ?? 0;
            $number_booking = Booking::countBookingByServiceID($this->id, Auth::id()) ?? 0;
            if ($number_review >= $number_booking) {
                return false;
            }
        }
        return true;
    }

        public static function getReviewStats()
        {
            $reviewStats = [];
            if (!empty($list = setting_item("cruise_review_stats", []))) {
                $list = json_decode($list, true);
                foreach ($list as $item) {
                    $reviewStats[] = $item['title'];
                }
            }
            return $reviewStats;
        }

        public function getReviewDataAttribute()
        {
            $list_score = [
                'score_total'  => 0,
                'score_text'   => __("Not Rated"),
                'total_review' => 0,
                'rate_score'   => [],
            ];
            $dataTotalReview = $this->reviewClass::selectRaw(" AVG(rate_number) as score_total , COUNT(id) as total_review ")->where('object_id', $this->id)->where('object_model', "cruise")->where("status", "approved")->first();
            if (!empty($dataTotalReview->score_total)) {
                $list_score['score_total'] = number_format($dataTotalReview->score_total, 1);
                $list_score['score_text'] = $this->reviewClass::getDisplayTextScoreByLever(round($list_score['score_total']));
            }
            if (!empty($dataTotalReview->total_review)) {
                $list_score['total_review'] = $dataTotalReview->total_review;
            }
            for ($rate = 5; $rate >= 1; $rate--) {
                $number = $this->reviewClass::where('rate_number', $rate)->where('object_id', $this->id)->where('object_model', "cruise")->where("status", "approved")->count();
                if (!empty($list_score['total_review'])) {
                    $percent = ($number / $list_score['total_review']) * 100;
                } else {
                    $percent = 0;
                }
                $list_score['rate_score'][$rate] = [
                    'title'   => $this->reviewClass::getDisplayTextScoreByLever($rate),
                    'total'   => $number,
                    'percent' => round($percent),
                ];
            }
            return $list_score;
        }

        /**
         * Get Score Review
         *
         * Using for loop cruise
         */
        public function getScoreReview()
        {
            $cruise_id = $this->id;
            $list_score = Cache::rememberForever('review_' . $this->type . '_' . $cruise_id, function () use ($cruise_id) {
                $dataReview = $this->reviewClass::selectRaw(" AVG(rate_number) as score_total , COUNT(id) as total_review ")->where('object_id', $cruise_id)->where('object_model', "cruise")->where("status", "approved")->first();
                return [
                    'score_total'  => !empty($dataReview->score_total) ? number_format($dataReview->score_total, 1) : 0,
                    'total_review' => !empty($dataReview->total_review) ? $dataReview->total_review : 0,
                ];
            });
            return $list_score;
        }

        public function getNumberReviewsInService($status = false)
        {
            return $this->reviewClass::countReviewByServiceID($this->id, false, $status, $this->type) ?? 0;
        }

        public function getNumberServiceInLocation($location)
        {
            if (!empty($location)) {
                $number = parent::join('bravo_locations', function ($join) use ($location) {
                    $join->on('bravo_locations.id', '=', 'bravo_cruises.location_id')->where('bravo_locations._lft', '>=', $location->_lft)->where('bravo_locations._rgt', '<=', $location->_rgt);
                })->where("bravo_cruises.status", "publish")->count("bravo_cruises.id");
            }
            if ($number > 1) {
                return __(":number Cruises", ['number' => $number]);
            }
            return __(":number Cruise", ['number' => $number]);
        }

        /**
         * @param $from
         * @param $to
         * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
         */
        public function getBookingsInRange($from, $to)
        {

            $query = Booking::query();
            $query->whereNotIn('status', ['draft']);
            $query->where('start_date', '<=', $to)->where('end_date', '>=', $from)->take(50);

            $query->where('object_id', $this->id);
            $query->where('object_model', 'cruise');

            return $query->orderBy('id', 'asc')->get();

        }

        public function saveCloneByID($clone_id)
        {
            $old = parent::find($clone_id);
            if (empty($old)) return false;
            $selected_terms = $old->cruise_term->pluck('term_id');
            $old->title = $old->title . " - Copy";
            $new = $old->replicate();
            $new->save();
            //Terms
            foreach ($selected_terms as $term_id) {
                $this->cruiseTermClass::firstOrCreate([
                    'term_id' => $term_id,
                    'cruise_id' => $new->id
                ]);
            }
            //Language
            $langs = $this->cruiseTranslationClass::where("origin_id", $old->id)->get();
            if (!empty($langs)) {
                foreach ($langs as $lang) {
                    $langNew = $lang->replicate();
                    $langNew->origin_id = $new->id;
                    $langNew->save();
                    $langSeo = SEO::where('object_id', $lang->id)->where('object_model', $lang->getSeoType() . "_" . $lang->locale)->first();
                    if (!empty($langSeo)) {
                        $langSeoNew = $langSeo->replicate();
                        $langSeoNew->object_id = $langNew->id;
                        $langSeoNew->save();
                    }
                }
            }
            //SEO
            $metaSeo = SEO::where('object_id', $old->id)->where('object_model', $this->seo_type)->first();
            if (!empty($metaSeo)) {
                $metaSeoNew = $metaSeo->replicate();
                $metaSeoNew->object_id = $new->id;
                $metaSeoNew->save();
            }
            //Meta
            $metaCruise = $this->cruiseMetaClass::where('cruise_id', $old->id)->first();
            if (!empty($metaCruise)) {
                $metaCruiseNew = $metaCruise->replicate();
                $metaCruiseNew->cruise_id = $new->id;
                $metaCruiseNew->save();
            }
        }

        public function hasWishList()
        {
            return $this->hasOne($this->userWishListClass, 'object_id', 'id')->where('object_model', $this->type)->where('user_id', Auth::id() ?? 0);
        }

        public function isWishList()
        {
            if (Auth::id()) {
                if (!empty($this->hasWishList) and !empty($this->hasWishList->id)) {
                    return 'active';
                }
            }
            return '';
        }
    }
