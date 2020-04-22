<?php
namespace Modules\Dashboard\Admin;

use Illuminate\Http\Request;
use Modules\AdminController;
use Modules\Booking\Models\Booking;

class DashboardController extends AdminController {
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
		$data = [
			'recent_bookings' => Booking::getRecentBookings($user_id, $user_type, 10),
			'top_cards' => Booking::getTopCardsReport($user_id, $user_type),
			'earning_chart_data' => Booking::getDashboardChartData($user_id, $f, time(), $user_type),
		];
		return view('Dashboard::index', $data);
	}

	public function reloadChart(Request $request) {
		$user_id = \Auth::user()->id;
		$user_type = 'administrator';
		if (\Auth::user()->hasRole('customer')) {
			$user_type = 'customer';
		} else if (\Auth::user()->hasRole('vendor')) {
			$user_type = 'vendor';
		} else if (\Auth::user()->hasRole('agent')) {
			$user_type = 'agent';
		}

		$chart = $request->input('chart');
		switch ($chart) {
		case "earning":
			$from = $request->input('from');
			$to = $request->input('to');
			$this->sendSuccess([
				'data' => Booking::getDashboardChartData($user_id, strtotime($from), strtotime($to), $user_type),
			]);
			break;
		}
	}
}