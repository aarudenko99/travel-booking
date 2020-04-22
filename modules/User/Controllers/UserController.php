<?php
namespace Modules\User\Controllers;

use App\Helpers\ReCaptchaEngine;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rule;
use Matrix\Exception;
use Modules\Booking\Models\Booking;
use Modules\FrontendController;
use Modules\User\Models\Subscriber;
use Modules\User\Models\User;
use Validator;

class UserController extends FrontendController {
	use AuthenticatesUsers;

	public function dashboard(Request $request) {
		$this->checkPermission('tour_view');
		$user_id = Auth::id();
		$data = [
			'cards_report' => Booking::getTopCardsReportForVendor($user_id),
			'earning_chart_data' => Booking::getEarningChartDataForVendor(strtotime('monday this week'), time(), $user_id),
			'page_title' => __("Vendor Dashboard"),
			'breadcrumbs' => [
				[
					'name' => __('Dashboard'),
					'class' => 'active',
				],
			],
		];
		return view('User::frontend.dashboard', $data);
	}

	public function reloadChart(Request $request) {
		$chart = $request->input('chart');
		$user_id = Auth::id();
		switch ($chart) {
		case "earning":
			$from = $request->input('from');
			$to = $request->input('to');
			$this->sendSuccess([
				'data' => Booking::getEarningChartDataForVendor(strtotime($from), strtotime($to), $user_id),
			]);
			break;
		}
	}

	public function profile(Request $request) {
		$user = Auth::user();
		if (!empty($request->input())) {

			$request->validate([
				'first_name' => 'required|max:255',
				'last_name' => 'required|max:255',
				'email' => [
					'required',
					'email',
					'max:255',
					Rule::unique('users')->ignore($user->id),
				],
			]);

			$user->fill($request->input());
			$user->birthday = date("Y-m-d", strtotime($user->birthday));
			$user->save();
			return redirect()->back()->with('success', __('Update successfully'));
		}
		$data = [
			'dataUser' => $user,
			'page_title' => __("Profile"),
			'breadcrumbs' => [
				[
					'name' => __('Setting'),
					'class' => 'active',
				],
			],
		];
		return view('User::frontend.profile', $data);
	}

	public function changePassword(Request $request) {
		if (!empty($request->input())) {
			if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
				// The passwords matches
				return redirect()->back()->with("error", __("Your current password does not matches with the password you provided. Please try again."));
			}
			if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
				//Current password and new password are same
				return redirect()->back()->with("error", __("New Password cannot be same as your current password. Please choose a different password."));
			}
			$request->validate([
				'current-password' => 'required',
				'new-password' => 'required|string|min:6|confirmed',
			]);
			//Change Password
			$user = Auth::user();
			$user->password = bcrypt($request->get('new-password'));
			$user->save();
			return redirect()->back()->with('success', __('Password changed successfully !'));
		}

		$data = [
			'breadcrumbs' => [
				[
					'name' => __('Setting'),
					'url' => 'user/profile',
				],
				[
					'name' => __('Change Password'),
					'class' => 'active',
				],
			],
			'page_title' => __("Change Password"),
		];
		return view('User::frontend.changePassword', $data);
	}

	public function bookingHistory(Request $request) {
		$user_id = Auth::id();

		$bookings = [];

		if (Auth::user()->hasRole('agent')) {
			$bookings = Booking::getBookingHistory($request->input('status'), false, false, false, $user_id);
		} elseif (Auth::user()->hasRole('vendor')) {
			$bookings = Booking::getBookingHistory($request->input('status'), false, $user_id);
		} else {
			$bookings = Booking::getBookingHistory($request->input('status'), $user_id);
		}

		$data = [
			'bookings' => $bookings,
			'statues' => config('booking.statuses'),
			'breadcrumbs' => [
				[
					'name' => __('Booking History'),
					'class' => 'active',
				],
			],
			'page_title' => __("Booking History"),
		];
		return view('User::frontend.bookingHistory', $data);
	}

	public function userLogin(Request $request) {
		$rules = [
			'email' => 'required|email',
			'password' => 'required',
		];
		$messages = [
			'email.required' => __('Email is required field'),
			'email.email' => __('Email invalidate'),
			'password.required' => __('Password is required field'),
		];
		if (ReCaptchaEngine::isEnable() and setting_item("user_enable_login_recaptcha")) {
			$codeCapcha = $request->input('g-recaptcha-response');
			if (!$codeCapcha or !ReCaptchaEngine::verify($codeCapcha)) {
				$errors = new MessageBag(['message_error' => __('Please verify the captcha')]);
				return response()->json(['error' => true,
					'messages' => $errors,
				], 200);
			}
		}
		$validator = Validator::make($request->all(), $rules, $messages);
		if ($validator->fails()) {
			return response()->json(['error' => true,
				'messages' => $validator->errors(),
			], 200);
		} else {
			$email = $request->input('email');
			$password = $request->input('password');
			if (Auth::attempt(['email' => $email,
				'password' => $password,
			], $request->has('remember'))) {
				if (in_array(Auth::user()->status, ['blocked'])) {
					Auth::logout();
					$errors = new MessageBag(['message_error' => __('Your account has been blocked')]);
					return response()->json([
						'error' => true,
						'messages' => $errors,
						'redirect' => false,
					], 200);

				}
				$user = Auth::User();
				$agent_id = $user->agent_id;
				$isFormBook = $request->input('isFormBook');
				if ($isFormBook == "true") {
					$agent_id = $request->input('agent_id');
					$user->agent_id = $agent_id;
					$user->save();
				}
				
				$customer = \App\User::query()->where('email', $email)->role('customer')->first(); //is_customer add
				return response()->json([
					'error' => false,
					'messages' => false,
					'is_customer' => ($customer) ? true : false,
					'redirect' => $request->headers->get('referer') ?? url(app_get_locale(false, '/')),
				], 200);
			} else {
				$errors = new MessageBag(['message_error' => __('Username or password incorrect')]);
				return response()->json([
					'error' => true,
					'messages' => $errors,
					'redirect' => false,
				], 200);
			}
		}
	}

	public function userRegister(Request $request) {
		$rules = [
			'first_name' => [
				'required',
				'string',
				'max:255',
			],
			'last_name' => [
				'required',
				'string',
				'max:255',
			],
			'email' => [
				'required',
				'string',
				'email',
				'max:255',
				'unique:users',
			],
			'password' => [
				'required',
				'string',
			],
			'term' => ['required'],
		];
		$messages = [
			'email.required' => __('Email is required field'),
			'email.email' => __('Email invalidate'),
			'password.required' => __('Password is required field'),
			'first_name.required' => __('The first name is required field'),
			'last_name.required' => __('The last name is required field'),
			'term.required' => __('The terms and conditions field is required'),
		];

		if (ReCaptchaEngine::isEnable() and setting_item("user_enable_register_recaptcha")) {
			$codeCapcha = $request->input('g-recaptcha-response');
			if (!$codeCapcha or !ReCaptchaEngine::verify($codeCapcha)) {
				$errors = new MessageBag(['message_error' => __('Please verify the captcha')]);
				return response()->json(['error' => true,
					'messages' => $errors,
				], 200);
			}
		}

		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			return response()->json(['error' => true,
				'messages' => $validator->errors(),
			], 200);
		} else {

			$user = new \App\User();
			$user->first_name = $request->input('first_name');
			$user->last_name = $request->input('last_name');
			$user->email = $request->input('email');
			$user->password = Hash::make($request->input('password'));
			$user->status = 'publish';

			// $isFormBook = $request->input('isFormBook');
			// if ($isFormBook == true) {
			// 	$agent_id = $request->input('agent_id');
			// 	$user->agent_id = $agent_id;
			// 	// $user->save();
			// }

			$isFormBook = $request->input('isFormBook');
			if ($isFormBook == "true") {
				$agent_id = $request->input('agent_id');
				$user->agent_id = $agent_id;
				// $user->save();
			}

			$user->save();

			Auth::loginUsingId($user->id);
			try {

				//event(new SendMailUserRegistered($user));

			} catch (Exception $exception) {

				Log::warning("SendMailUserRegistered: " . $exception->getMessage());

			}
			$user->assignRole('customer');
			return response()->json([
				'error' => false,
				'messages' => false,
				'redirect' => $request->headers->get('referer') ?? url(app_get_locale(false, '/')),
			], 200);
		}
	}

	public function subscribe(Request $request) {

		$this->validate($request, [
			'email' => 'required|email|max:255',
		]);
		$check = Subscriber::withTrashed()->where('email', $request->input('email'))->first();
		if ($check) {
			if ($check->trashed()) {
				$check->restore();
				$this->sendSuccess([], __('Thank you for subscribing'));
			}
			$this->sendError(__('You are already subscribed'));
		} else {
			$a = new Subscriber();
			$a->email = $request->input('email');
			$a->first_name = $request->input('first_name');
			$a->last_name = $request->input('last_name');
			$a->save();
			$this->sendSuccess([], __('Thank you for subscribing'));
		}
	}

	public function logout(Request $request) {
		$this->guard()->logout();

		$request->session()->invalidate();

		return redirect(app_get_locale(false, '/'));
	}

	public function pushAgentId(Request $request) {

		$is_valid_agent_id = $request->input('is_valid_agent_id');
		if ($is_valid_agent_id == 1) {
			$agent_id = $request->input('agent_id');
		} elseif ($is_valid_agent_id == 0) {
			$agent_id = 0;
		}
		$user = Auth::user();
		$user->agent_id = $agent_id;

		if ($user->save()) {
			return response()->json([
				'redirect' => $request->headers->get('referer') ?? url(app_get_locale(false, '/')),
			], 200);
		} else {
			return response()->json([
				'redirect' => false,
			], 200);
		}
	}

	public function agent(Request $request) {
		$user = Auth::user();
		$user_agent_id = $user->agent_id;
		$agent = \App\User::query()->where('id', $user_agent_id)->first();
		$agent_name = "";
		$user_agent_id = "";
		if ($agent) {
			$agent_name = $agent->name;
			$user_agent_id = $user->agent_id;
		}
		return response()->json([
			'agent_name' => $agent_name,
			'user_agent_id' => $user_agent_id,
		], 200);
	}

	public function confirmAgentId(Request $request) {
		$agent_id = $request->input('agent_id');
		$row = \App\User::query()->where('id', $agent_id)->role('agent')->first();

		if ($row) {
			return response()->json([
				'agent' => true,
				'agent_id' => $row->id,
			], 200);
		} else {
			return response()->json([
				'agent' => false,
				'agent_id' => 0,
			], 200);
		}
	}

	public function verifySuccess(Request $request) {
		$user_type = 'administrator';
		if (Auth::user()->hasRole('customer')) {
			$user_type = 'customer';
		} else if (Auth::user()->hasRole('vendor')) {
			$user_type = 'vendor';
		} else if (Auth::user()->hasRole('agent')) {
			$user_type = 'agent';
		}

		return view('auth.verify-success', array('user_type' => $user_type));
	}
}
