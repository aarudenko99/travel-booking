<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
//    use Modules\Vendor\Models\VendorPlan;
use Modules\Review\Models\Review;
use Modules\User\Emails\ResetPasswordToken;
use Modules\Vendor\Models\VendorRequest;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail {
	use SoftDeletes;
	use Notifiable;
	use HasRoles;
//        protected $appends = ['vendorPlanData'];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
		'first_name',
		'last_name',
		'email',
		'email_verified_at',
		'password',
		'address',
		'address2',
		'phone',
		'birthday',
		'city',
		'state',
		'country',
		'zip_code',
		'last_login_at',
		'avatar_id',
		'bio',
//            'vendor_plan_id',
		//            'vendor_plan_enable',
		//            'vendor_plan_start_date',
		//            'vendor_plan_end_date',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	public function getMeta($key, $default = '') {
		//if(isset($this->cachedMeta[$key])) return $this->cachedMeta[$key];

		$val = DB::table('user_meta')->where([
			'user_id' => $this->id,
			'name' => $key,
		])->first();

		if (!empty($val)) {
			//$this->cachedMeta[$key]  = $val->val;
			return $val->val;
		}

		return $default;
	}

	public function addMeta($key, $val, $multiple = false) {

		if ($multiple) {
			return DB::table('user_meta')->insert([
				'name' => $key,
				'val' => $val,
				'user_id' => $this->id,
			]);
		} else {
			$old = DB::table('user_meta')->where([
				'user_id' => $this->id,
				'name' => $key,
			])->first();

			if ($old) {
				return DB::table('user_meta')->where('id', $old['id'])->insert([
					'val' => $val,
				]);
			} else {
				return DB::table('user_meta')->insert([
					'name' => $key,
					'val' => $val,
					'user_id' => $this->id,
				]);
			}
		}

	}

	public function batchInsertMeta($metaArrs = []) {
		if (!empty($metaArrs)) {
			foreach ($metaArrs as $key => $val) {
				$this->addMeta($key, $val, true);
			}
		}
	}

	public function getNameOrEmailAttribute() {
		if ($this->first_name) {
			return $this->first_name;
		}

		return $this->email;
	}

	public function getStatusTextAttribute() {
		switch ($this->status) {
		case "publish":
			return __("Publish");
			break;
		case "blocked":
			return __("Blocked");
			break;
		}
	}

	public static function getUserBySocialId($provider, $socialId) {
		parent::join('user_meta as m', 'm.user_id', 'users.id')
			->where('m.name', 'social_' . $provider . '_id')
			->where('m.val', $socialId)->first();
	}

	public function getAvatarUrl() {
		if (empty($this->avatar_id)) {
			return false;
		}
		$avatar_url = get_file_url($this->avatar_id, 'thumb');
		return $avatar_url;
	}

	public function getDisplayName() {
		if (empty($this->first_name) and empty($this->last_name) and !empty($this->name)) {
			return $this->name;
		} else {
			return implode(' ', [$this->first_name, $this->last_name]);
		}
	}

	public function sendPasswordResetNotification($token) {
		Mail::to($this->email)->send(new ResetPasswordToken($token));
	}

	public static function boot() {
		parent::boot();
		static::saving(function ($table) {
			$table->name = implode(' ', [$table->first_name, $table->last_name]);
		});
	}

//        public function vendorPlan()
	//        {
	//            return $this->belongsTo(VendorPlan::class, 'vendor_plan_id');
	//        }
	//
	//        public function getVendorPlanDataAttribute()
	//        {
	//            $data = [];
	//            if ($this->hasRole('vendor')) {
	//                $plan = $this->vendorPlan()->first();
	//                if (!empty($plan)) {
	//                    $enable = !empty($this->vendor_plan_enable) ? true : false;
	//                    if ($plan->status == 'publish') {
	//                        $enable = true;
	//                    } else {
	//                        $enable = false;
	//                    }
	//                    $data['vendor_plan_enable'] = $enable;
	//                    $data['base_commission'] = $plan->base_commission;
	//                    $planMeta = $plan->meta;
	//                    foreach ($planMeta as $meta){
	//                        $data[$meta->post_type] = $meta->toArray();
	//                    }
	//                }
	//            }
	//            return $data;
	//        }

	public function getVendorServicesQuery($moduleClass, $limit = 10) {
		return $moduleClass::getVendorServicesQuery()->take($limit);
	}

	public function getReviewCountAttribute() {
		return Review::query()->where('vendor_id', $this->id)->where('status', 'approved')->count('id');
	}
	public function vendorRequest() {
		return $this->hasOne(VendorRequest::class);
	}
}
