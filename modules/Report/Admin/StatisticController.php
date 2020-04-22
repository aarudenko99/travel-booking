<?php
namespace Modules\Report\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\AdminController;
use Modules\Booking\Models\Booking;

class StatisticController extends AdminController {
	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$user_id = \Auth::user()->id;
		$user_type = 'administrator';
		if (\Auth::user()->hasRole('customer')) {
			$user_type = 'customer';
		} else if (\Auth::user()->hasRole('vendor')) {
			$user_type = 'vendor';
		} else if (\Auth::user()->hasRole('agent')) {
			$user_type = 'agent';
		}

		$f = strtotime('monday this week');
		$status = config('booking.statuses');
		$data = [
			'earning_chart_data' => Booking::getStatisticChartData($user_id, $f, time(), $status, $customer_id = false, $vendor_id = false, $user_type)['chart'],
			'earning_detail_data' => Booking::getStatisticChartData($user_id, $f, time(), $status, $customer_id = false, $vendor_id = false, $user_type)['detail'],
		];
		return view('Report::admin.statistic.index', $data);
	}

	public function reloadChart(Request $request) {
		$user_id = \Auth::user()->id;
		$account_user_type = 'administrator';
		if (\Auth::user()->hasRole('customer')) {
			$account_user_type = 'customer';
		} else if (\Auth::user()->hasRole('vendor')) {
			$account_user_type = 'vendor';
		} else if (\Auth::user()->hasRole('agent')) {
			$account_user_type = 'agent';
		}

		$from = $request->input('from');
		$to = $request->input('to');
		$status = config('booking.statuses');
		$customer_id = false;
		$vendor_id = false;
		$user_type = $request->input('user_type');
		if ($user_type == 'customer') {
			$customer_id = $request->input('user_id');
		}
		if ($user_type == 'vendor') {
			$vendor_id = $request->input('user_id');
		}

		$this->sendSuccess([
			'chart_data' => Booking::getStatisticChartData($user_id, strtotime($from), strtotime($to), $status, $customer_id, $vendor_id, $account_user_type)['chart'],
			'detail_data' => Booking::getStatisticChartData($user_id, strtotime($from), strtotime($to), $status, $customer_id, $vendor_id, $account_user_type)['detail'],
		]);
	}
}