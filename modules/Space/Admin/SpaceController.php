<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/30/2019
 * Time: 1:56 PM
 */
namespace Modules\Space\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\AdminController;
use Modules\Core\Models\Attributes;
use Modules\Location\Models\Location;
use Modules\Space\Models\Space;
use Modules\Space\Models\SpaceTerm;
use Modules\Space\Models\SpaceTranslation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;

ini_set('memory_limit','77824M');
set_time_limit(600);
class SpaceController extends AdminController
{
    protected $space;
    protected $space_translation;
    protected $space_term;
    protected $attributes;
    protected $location;
    public function __construct()
    {
        parent::__construct();
        $this->setActiveMenu('admin/module/space');
        $this->space = Space::class;
        $this->space_translation = SpaceTranslation::class;
        $this->space_term = SpaceTerm::class;
        $this->attributes = Attributes::class;
        $this->location = Location::class;
    }

    public function index(Request $request)
    {
        $this->checkPermission('space_view');
        $query = $this->space::query() ;
        $query->orderBy('id', 'desc');
        if (!empty($space_name = $request->input('s'))) {
            $query->where('title', 'LIKE', '%' . $space_name . '%');
            $query->orderBy('title', 'asc');
        }

        if ($this->hasPermission('space_manage_others')) {
            if (!empty($author = $request->input('vendor_id'))) {
                $query->where('create_user', $author);
            }
        } else {
            $query->where('create_user', Auth::id());
        }
        $data = [
            'rows'               => $query->with(['author'])->paginate(20),
            'space_manage_others' => $this->hasPermission('space_manage_others'),
            'breadcrumbs'        => [
                [
                    'name' => __('Spaces'),
                    'url'  => 'admin/module/tour'
                ],
                [
                    'name'  => __('All'),
                    'class' => 'active'
                ],
            ],
            'page_title'=>__("Space Management")
        ];
        return view('Space::admin.index', $data);
    }

    public function create(Request $request)
    {
        $this->checkPermission('space_create');
        $row = new $this->space();
        $row->fill([
            'status' => 'publish'
        ]);
        $data = [
            'row'            => $row,
            'attributes'     => $this->attributes::where('service', 'space')->get(),
            'space_location' => $this->location::where('status', 'publish')->get()->toTree(),
            'translation'    => new $this->space_translation(),
            'breadcrumbs'    => [
                [
                    'name' => __('Spaces'),
                    'url'  => 'admin/module/space'
                ],
                [
                    'name'  => __('Add Space'),
                    'class' => 'active'
                ],
            ],
            'page_title'     => __("Add new Space")
        ];
        return view('Space::admin.detail', $data);
    }

    public function edit(Request $request, $id)
    {
        $this->checkPermission('space_update');
        $row = $this->space::find($id);
        if (empty($row)) {
            return redirect(route('space.admin.index'));
        }
        $translation = $row->translateOrOrigin($request->query('lang'));
        if (!$this->hasPermission('space_manage_others')) {
            if ($row->create_user != Auth::id()) {
                return redirect(route('space.admin.index'));
            }
        }
        $data = [
            'row'            => $row,
            'translation'    => $translation,
            "selected_terms" => $row->terms->pluck('term_id'),
            'attributes'     => $this->attributes::where('service', 'space')->get(),
            'space_location'  => $this->location::where('status', 'publish')->get()->toTree(),
            'enable_multi_lang'=>true,
            'breadcrumbs'    => [
                [
                    'name' => __('Spaces'),
                    'url'  => 'admin/module/space'
                ],
                [
                    'name'  => __('Edit Space'),
                    'class' => 'active'
                ],
            ],
            'page_title'=>__("Edit: :name",['name'=>$row->title])
        ];
        return view('Space::admin.detail', $data);
    }
    
    ///////////////////////// insert space data from CSV to DB////////////////////////////////////
    function csvToArray($filename = '', $delimiter = ',') {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 5000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        
        return $data;
    }
    
    function spacePrice($price_index, $target_id) {
        $price = [];
        $arr_prices = explode("|", $price_index);
        $arr_prices = str_replace(' ', '', $arr_prices);
        $count_arr_price = count($arr_prices);
        $date_period_and_price = [];
        for ($x = 0; $x < $count_arr_price; $x++) {
            $date_prices = $arr_prices[$x];
            $date_prices = explode(":", $date_prices);
            $date_period = explode("-", $date_prices[0]);
            if(empty($date_prices[0])){
                continue;
            }
            $date_period_and_price[] = array(
                "date_period" => explode("-", $date_prices[0]),
                "price" => $date_prices[2]
            );  
            $numbers = array_column($date_period_and_price, 'price');
            $price = min($numbers);
        }
        if(!empty($price)){
            $query = $this->space::where("id", $target_id);
            $query->update(['price' => $price]);
        }
        // dd($query);
        
        for ($i = 0; $i < 365*3; $i++){
            $Date = "2019-01-01";
            $curDate =  date('m-d', strtotime($Date. " + $i days"));
            $curDateYear =  date('Y-m-d', strtotime($Date. " + $i days"));
            for ($x = 0; $x < count($date_period_and_price); $x++){
                $first_date = $date_period_and_price[$x]["date_period"][0];
                $last_date = $date_period_and_price[$x]["date_period"][1];
                $firstDate = date_create_from_format('d.m', $first_date)->format('m-d');
                $lastDate = date_create_from_format('d.m', $last_date)->format('m-d');
                if($curDate >= $firstDate && $curDate <= $lastDate){
                    if($date_period_and_price[$x]["price"] == 0){
                        DB::table('bravo_space_dates')->insert([
                            'start_date' => $curDateYear,	
                            'end_date' => $curDateYear,
                            'target_id' => $target_id,
                            'price' => $date_period_and_price[$x]["price"],
                            'active' => 0,
                            'is_instant' => 1,
                            'created_at' => date('Y-m-d'),
                            'updated_at' => date('Y-m-d')
                        ]);
                        break;
                    }
                    DB::table('bravo_space_dates')->insert([
                        'start_date' => $curDateYear,	
                        'end_date' => $curDateYear,
                        'target_id' => $target_id,
                        'price' => $date_period_and_price[$x]["price"],
                        'active' => 1,
                        'is_instant' => 1,
                        'created_at' => date('Y-m-d'),
                        'updated_at' => date('Y-m-d')
                    ]);
                    break;
                }
            }
            
        }
    }
    function getImageId($image_array){
        $IDs = [];
        for ($x = 0; $x < count($image_array); $x++){
            $file_path = "demo/space/gallery/".trim($image_array[$x]);
            $file_name = explode(".", trim($image_array[$x]))[0];
            if(empty($file_name)){
                continue;
            }
            // dd("-----file_extension------",$file_name);
            $file_extension = explode(".", trim($image_array[$x]))[1];
            $file_type = "image/".$file_extension;
            $IDs[] = DB::table('media_files')->insertGetId([
                'file_name' => $file_name,
                'file_path' => $file_path,
                'file_type' => $file_type,
                'file_extension' => $file_extension
            ]);
        }
        return $IDs;
    }

    function insetAttribute($amenities_list, $appliances, $target_id){
        //Amentities
        for ($x = 0; $x < count($amenities_list); $x++){
            $element_amentity = trim($amenities_list[$x]);
            $term_result = DB::table('bravo_terms')->where([
				'name' => $element_amentity,
				'attr_id' => 3,
            ])->first('id');
            if(!$term_result){
                $slug = str_replace(' ', '-', $element_amentity);
                $slug = strtolower($slug);
                $term_id = DB::table('bravo_terms')->insertGetId([
                    'name' => $element_amentity,
                    'attr_id' => 3,
                    'slug' => strtolower($slug),
                    'create_user' => 1,
                    'created_at' => date('Y-m-d'),
                    'updated_at' => date('Y-m-d')
                    ]);
            } else{
                $term_id = $term_result->id;
            }
            DB::table('bravo_space_term')->insert([
            'term_id' => $term_id,
            'target_id' => $target_id,
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d')
            ]);
        }
        //Appliances
        for ($x = 0; $x < count($appliances); $x++){
            $element_appliances = trim($appliances[$x]);
            $term_result = DB::table('bravo_terms')->where([
				'name' => $element_appliances,
				'attr_id' => 4,
            ])->first('id');
            if(!$term_result){
                $slug = str_replace(' ', '-', $element_appliances);
                $slug = strtolower($slug);
                $term_id = DB::table('bravo_terms')->insertGetId([
                    'name' => $element_appliances,
                    'attr_id' => 4,
                    'slug' => strtolower($slug),
                    'create_user' => 1,
                    'created_at' => date('Y-m-d'),
                    'updated_at' => date('Y-m-d')
                    ]);
                } else{
                    $term_id = $term_result->id;
                }
                $term_result = DB::table('bravo_space_term')->insertGetId([
                    'term_id' => $term_id,
                'target_id' => $target_id,
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d')
                ]);
        }
    }

    function getLocationId($city_name, $map_lat, $map_lng){
        $location_name = trim($city_name);
        $location_slug = Str::slug($location_name, '-');
        $location_result = DB::table('bravo_locations')->where([
            'slug' => $location_slug
            ])->first('id');
        if(!$location_result){
            $location_id = DB::table('bravo_locations')->insertGetId([
                'name' => $location_name,
                'slug' => $location_slug,
                'create_user' => 1,
                'map_lat' => $map_lat,
                'map_lng' => $map_lng,
                'map_zoom' => '12',
                'status' => 'publish',
                'created_at' =>  date("Y-m-d H:i:s")
                ]);
        } else{
            $location_id = $location_result->id;
        }
        return $location_id;
    }

    function getVendorId($vendor_firstName, $vendor_lastName, $vendor_PhoneNumber, $vendor_address, $vendor_city){
        $vendor_name = $vendor_firstName." ".$vendor_lastName;
        $first_name = str_replace('ž', 'z', $vendor_firstName);
        $first_name = str_replace('đ', 'd', $first_name);
        $first_name = str_replace('š', 's', $first_name);
        $first_name = str_replace('č', 'c', $first_name);
        $first_name = str_replace('ć', 'c', $first_name);

        $last_name = str_replace('ž', 'z', $vendor_lastName);
        $last_name = str_replace('đ', 'd', $last_name);
        $last_name = str_replace('š', 's', $last_name);
        $last_name = str_replace('č', 'c', $last_name);
        $last_name = str_replace('ć', 'c', $last_name);

        $vendor_PhoneNumber = str_replace("'", "", $vendor_PhoneNumber);

        $address = explode(",", $vendor_address)[0];
        $email = strtolower($first_name).".".strtolower($last_name)."@dataentry.pro";
        // $email = "ive.sucic@dataentry.pro";
        $password = '$2y$10$MYovar3gYE2VN3VJPOzWhOQtMlTGnOIURExEZkNShU/dm6pFWvSza';
        //email exist verify
        $email_isOn = DB::table('users')->where([
            'email' => $email
            ])->first('id');
        if(!$email_isOn){
            $user_vendor_id = DB::table('users')->insertGetId([
                'name' => $vendor_name,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'address' => $address,
                'city' => $vendor_city,
                // 'country' => "Croatia",
                'email_verified_at' => date("Y-m-d H:i:s"),
                'password' => $password,
                'phone' => $vendor_PhoneNumber,
                'created_at' =>  date("Y-m-d H:i:s")
                ]);
            DB::table('core_model_has_roles')->insert([
                'role_id' => 1,
                'model_type' => 'App\User',
                'model_id' => $user_vendor_id
                ]);
        } else{
            //phone number exist verify
            $phone_isOn = DB::table('users')->where([
                'phone' => $vendor_PhoneNumber
                ])->first('id');
            if(!$phone_isOn) {
                for ($i = 2; $i > 0; $i++){
                    $email = strtolower($first_name).".".strtolower($last_name).$i."@dataentry.pro";
                    $isExist = DB::table('users')->where([
                        'email' => $email
                        ])->first('id');
                    if(!$isExist){
                        break;
                    }     
                }
                $user_vendor_id = DB::table('users')->insertGetId([
                    'name' => $vendor_name,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'email_verified_at' => date("Y-m-d H:i:s"),
                    'password' => $password,
                    'phone' => $vendor_PhoneNumber,
                    'created_at' =>  date("Y-m-d H:i:s")
                    ]);
                DB::table('core_model_has_roles')->insert([
                    'role_id' => 1,
                    'model_type' => 'App\User',
                    'model_id' => $user_vendor_id
                    ]);
            } else{
                $user_vendor_id = $phone_isOn->id;
            }
        }
        return $user_vendor_id;
    }

    public function addSpaceFromCsv(Request $request){
        $input = $request->file('csvFileName')->getRealPath();
        $customerArr = $this->csvToArray($input);
        for ($x = 0; $x < count($customerArr); $x++){

            // get vendor ID
            $vendor_firstName = $customerArr[$x]['FirstName'];
            $vendor_lastName = $customerArr[$x]['LastName'];
            $vendor_PhoneNumber = $customerArr[$x]['phone_number'];
            $vendor_address = $customerArr[$x]['address'];
            $vendor_city = $customerArr[$x]['city'];
            $user_vendor_id = $this->getVendorId($vendor_firstName, $vendor_lastName, $vendor_PhoneNumber, $vendor_address, $vendor_city);
            // dd("--end--",$user_vendor_id);
            //get location ID
            $city_name = $customerArr[$x]['city'];
            $map_lat = $customerArr[$x]['map_lat'];
            $map_lng = $customerArr[$x]['map_lng'];
            $location_id = $this->getLocationId($city_name, $map_lat, $map_lng);
            // get image id
            $image_array = explode("|", $customerArr[$x]['images']);
            if(empty($image_array)){
                continue;
            }
            // dd("---------image_array--------",$image_array);
            $list_gallery = $this->getImageId($image_array);

            $target_id = DB::table('bravo_spaces')->insertGetId(
                [
                    'title' => $customerArr[$x]['title'],
                    'slug' => Str::slug($customerArr[$x]['title'], '-'),
                    'content' => $customerArr[$x]['content'],
                    'image_id' => (!empty($list_gallery)) ? $list_gallery[rand(0, count($list_gallery)-1)]: '',
                    'banner_image_id' => (!empty($list_gallery)) ? $list_gallery[rand(0, count($list_gallery)-1)]: '',
                    'location_id' => $location_id,
                    'address' => $vendor_address,
                    'gallery' => implode(",",$list_gallery),
                    'price' => 0,    
                    'map_lat' => $customerArr[$x]['map_lat'],
                    'map_lng' => $customerArr[$x]['map_lng'],
                    'map_zoom' => "12",
                    'status' => "publish",
                    'create_user' => $user_vendor_id,
                    'created_at' =>  date("Y-m-d H:i:s"),
                    'bed' =>  $customerArr[$x]['map_lng'],
                    'bathroom' =>  $customerArr[$x]['bathroom'],
                    'square' =>  $customerArr[$x]['square'],
                    'max_guests' =>  $customerArr[$x]['max_guests'],
                    'default_state' => 0
                ]);
                //insert spaces date price to DB(bravo_space_dates)
                $price_index = $customerArr[$x]['price'];
                $this->spacePrice($price_index, $target_id);
                //insert attribute data
                $amenities_list = explode("|", $customerArr[$x]['attributes']);
                $appliances = explode("|", $customerArr[$x]['appliances']);
                $this->insetAttribute($amenities_list, $appliances, $target_id);
                print_r($customerArr[$x]['title']);
                print_r("---------complete-------");
                print_r("</br>");
        }
    }
////////////////////////////////////////////////////////////////////////////////////////////////////


    public function store( Request $request, $id ){

        if($id>0){
            $this->checkPermission('space_update');
            $row = $this->space::find($id);
            if (empty($row)) {
                return redirect(route('space.admin.index'));
            }

            if($row->create_user != Auth::id() and !$this->hasPermission('space_manage_others'))
            {
                return redirect(route('space.admin.index'));
            }
        }else{
            $this->checkPermission('space_create');
            $row = new $this->space();
            $row->status = "publish";
        }
        $dataKeys = [
            'title',
            'content',
            'slug',
            'price',
            'is_instant',
            'status',
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
                return redirect(route('space.admin.edit',$row->id))->with('success', __('Space created') );
            }
        }
    }

    public function saveTerms($row, $request)
    {
        $this->checkPermission('space_manage_attributes');
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

    public function bulkEdit(Request $request)
    {

        $ids = $request->input('ids');
        $action = $request->input('action');
        if (empty($ids) or !is_array($ids)) {
            return redirect()->back()->with('error', __('No items selected!'));
        }
        if (empty($action)) {
            return redirect()->back()->with('error', __('Please select an action!'));
        }

        switch ($action){
            case "delete":
                foreach ($ids as $id) {
                    $query = $this->space::where("id", $id);
                    if (!$this->hasPermission('space_manage_others')) {
                        $query->where("create_user", Auth::id());
                        $this->checkPermission('space_delete');
                    }
                    $query->first()->delete();
                }
                return redirect()->back()->with('success', __('Deleted success!'));
                break;
            case "clone":
                $this->checkPermission('space_create');
                foreach ($ids as $id) {
                    (new $this->space())->saveCloneByID($id);
                }
                return redirect()->back()->with('success', __('Clone success!'));
                break;
            default:
                // Change status
                foreach ($ids as $id) {
                    $query = $this->space::where("id", $id);
                    if (!$this->hasPermission('space_manage_others')) {
                        $query->where("create_user", Auth::id());
                        $this->checkPermission('space_update');
                    }
                    $query->update(['status' => $action]);
                }
                return redirect()->back()->with('success', __('Update success!'));
                break;
        }


    }
}