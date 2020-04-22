<?php
namespace Modules\Booking\Controllers;

use App\Helpers\ReCaptchaEngine;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
//use Modules\Booking\Events\VendorLogPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;
use Modules\Booking\Models\Booking;
use Validator;

class BookingController extends \App\Http\Controllers\Controller {
	use AuthorizesRequests;
	protected $booking;

	public function __construct() {
		$this->booking = Booking::class;
	}

	public function checkout($code) {

		$booking = $this->booking::where('code', $code)->first();

		if (empty($booking)) {
			abort(404);
		}
		if ($booking->customer_id != Auth::id()) {
			abort(404);
		}

		if ($booking->status != 'draft') {
			return redirect('/');
		}
		$user = Auth::user();
		$user_id = $user->id;
		$user_agent_id = $user->agent_id;
		$agent = \App\User::query()->where('id', $user_agent_id)->first();
		$customer = \App\User::query()->where('id', $user_id)->role('customer')->first();
		$agent_name = 'Please input your agent number.';
		if ($agent) {
			$agent_name = $agent->name;
		}

		$data = [
			'page_title' => __('Checkout'),
			'booking' => $booking,
			'service' => $booking->service,
			'gateways' => $this->getGateways(),
			'user' => Auth::user(),
			'agent_name' => $agent_name,
			'customer' => $customer,
			'agent_id' => $user_agent_id,
		];
		return view('Booking::frontend/checkout', $data);
	}

	public function checkStatusCheckout($code) {
		$booking = $this->booking::where('code', $code)->first();
		$data = [
			'error' => false,
			'message' => '',
			'redirect' => '',
		];
		if (empty($booking)) {
			$data = [
				'error' => true,
				'redirect' => url('/'),
			];
		}
		if ($booking->customer_id != Auth::id()) {
			$data = [
				'error' => true,
				'redirect' => url('/'),
			];
		}
		if ($booking->status != 'draft') {
			$data = [
				'error' => true,
				'redirect' => url('/'),
			];
		}
		return response()->json($data, 200);
	}

	public function doCheckout(Request $request) {

		/**
		 * @param Booking $booking
		 */
		$validator = Validator::make($request->all(), [
			'code' => 'required',
		]);
		if ($validator->fails()) {
			$this->sendError('', ['errors' => $validator->errors()]);
		}
		$code = $request->input('code');
		$booking = $this->booking::where('code', $code)->first();
		if (empty($booking)) {
			abort(404);
		}
		if ($booking->customer_id != Auth::id()) {
			abort(404);
		}
		if ($booking->status != 'draft') {
			return $this->sendError('', [
				'url' => $booking->getDetailUrl(),
			]);
		}
		$service = $booking->service;
		if (empty($service)) {
			$this->sendError(__("Service not found"));
		}
		/**
		 * Google ReCapcha
		 */
		if (ReCaptchaEngine::isEnable() and setting_item("booking_enable_recaptcha")) {
			$codeCapcha = $request->input('g-recaptcha-response');
			if (!$codeCapcha or !ReCaptchaEngine::verify($codeCapcha)) {
				$this->sendError(__("Please verify the captcha"));
			}
		}
		$rules = [
			'first_name' => 'required|string|max:255',
			'last_name' => 'required|string|max:255',
			'email' => 'required|string|email|max:255',
			'phone' => 'required|string|max:255',
			'payment_gateway' => 'required',
			'term_conditions' => 'required',
			'address_line_1' => 'required|string|max:255',
			'city' => 'required|string|max:255',
			'zip_code' => 'required|string|max:255',
			'country' => 'required|string|max:255',
			'agent' => 'required|string|max:255',
		];
		$rules = $service->filterCheckoutValidate($request, $rules);
		if (!empty($rules)) {
			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
				$this->sendError('', ['errors' => $validator->errors()]);
			}
		}
		if (!empty($rules['payment_gateway'])) {
			$payment_gateway = $request->input('payment_gateway');
			$gateways = config('booking.payment_gateways');
			if (empty($gateways[$payment_gateway]) or !class_exists($gateways[$payment_gateway])) {
				$this->sendError(__("Payment gateway not found"));
			}
			$gatewayObj = new $gateways[$payment_gateway]($payment_gateway);
			if (!$gatewayObj->isAvailable()) {
				$this->sendError(__("Payment gateway is not available"));
			}
		}
		$service->beforeCheckout($request, $booking);
		// Normal Checkout
		$booking->first_name = $request->input('first_name');
		$booking->last_name = $request->input('last_name');
		$booking->email = $request->input('email');
		$booking->phone = $request->input('phone');
		$booking->address = $request->input('address_line_1');
		$booking->address2 = $request->input('address_line_2');
		$booking->city = $request->input('city');
		$booking->state = $request->input('state');
		$booking->zip_code = $request->input('zip_code');
		$booking->country = $request->input('country');
		$booking->customer_notes = $request->input('customer_notes');
		$booking->gateway = $payment_gateway;
		$booking->agent_id = $request->input('agent');
		$booking->calculateAgentCommission();
		$booking->save();

//        event(new VendorLogPayment($booking));

		$user = Auth::user();
		$user->first_name = $request->input('first_name');
		$user->last_name = $request->input('last_name');
		$user->phone = $request->input('phone');
		$user->address = $request->input('address_line_1');
		$user->address2 = $request->input('address_line_2');
		$user->city = $request->input('city');
		$user->state = $request->input('state');
		$user->zip_code = $request->input('zip_code');
		$user->country = $request->input('country');
		$user->save();

		$booking->addMeta('locale', app()->getLocale());

		$service->afterCheckout($request, $booking);
		try {

			$gatewayObj->process($request, $booking, $service);
		} catch (Exception $exception) {
			$this->sendError($exception->getMessage());
		}
	}

	public function confirmPayment(Request $request, $gateway) {

		$gateways = config('booking.payment_gateways');
		if (empty($gateways[$gateway]) or !class_exists($gateways[$gateway])) {
			$this->sendError(__("Payment gateway not found"));
		}
		$gatewayObj = new $gateways[$gateway]($gateway);
		if (!$gatewayObj->isAvailable()) {
			$this->sendError(__("Payment gateway is not available"));
		}
		return $gatewayObj->confirmPayment($request);
	}

	public function cancelPayment(Request $request, $gateway) {

		$gateways = config('booking.payment_gateways');
		if (empty($gateways[$gateway]) or !class_exists($gateways[$gateway])) {
			$this->sendError(__("Payment gateway not found"));
		}
		$gatewayObj = new $gateways[$gateway]($gateway);
		if (!$gatewayObj->isAvailable()) {
			$this->sendError(__("Payment gateway is not available"));
		}
		return $gatewayObj->cancelPayment($request);
	}

	/**
	 * @todo Handle Add To Cart Validate
	 *
	 * @param Request $request
	 * @return string json
	 */
	public function addToCart(Request $request) {

		$validator = Validator::make($request->all(), [
			'service_id' => 'required|integer',
			'service_type' => 'required',
		]);
		if ($validator->fails()) {
			$this->sendError('', ['errors' => $validator->errors()]);
		}
		$service_type = $request->input('service_type');
		$service_id = $request->input('service_id');
		$allServices = get_bookable_services();
		if (empty($allServices[$service_type])) {
			$this->sendError(__('Service type not found'));
		}
		$module = $allServices[$service_type];
		$service = $module::find($service_id);
		if (empty($service) or !is_subclass_of($service, '\\Modules\\Booking\\Models\\Bookable')) {
			$this->sendError(__('Service not found'));
		}
		if (!$service->isBookable()) {
			$this->sendError(__('Service is not bookable'));
		}
		//        try{
		$service->addToCart($request);
		//
		//        }catch(\Exception $ex){
		//            $this->sendError($ex->getMessage(),['code'=>$ex->getCode()]);
		//        }
	}

	protected function getGateways() {

		$all = config('booking.payment_gateways');
		$res = [];
		foreach ($all as $k => $item) {
			if (class_exists($item)) {
				$obj = new $item($k);
				if ($obj->isAvailable()) {
					$res[$k] = $obj;
				}
			}
		}
		return $res;
	}

	public function detail(Request $request, $code) {

		$booking = Booking::where('code', $code)->first();
		if (empty($booking)) {
			abort(404);
		}

		if ($booking->status == 'draft') {
			return redirect($booking->getCheckoutUrl());
		}
		if ($booking->customer_id != Auth::id()) {
			abort(404);
		}
		$data = [
			'page_title' => __('Booking Details'),
			'booking' => $booking,
			'service' => $booking->service,
		];
		if ($booking->gateway) {
			$data['gateway'] = get_payment_gateway_obj($booking->gateway);
		}
		return view('Booking::frontend/detail', $data);
	}
}
