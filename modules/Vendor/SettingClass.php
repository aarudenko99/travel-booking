<?php

namespace Modules\Vendor;

use Modules\Core\Abstracts\BaseSettingsClass;

class SettingClass extends BaseSettingsClass {
	public static function getSettingPages() {
		return [
			[
				'id' => 'vendor',
				'title' => __("Vendor Settings"),
				'position' => 50,
				'view' => "Vendor::admin.settings.vendor",
				"keys" => [
					'vendor_enable',
					'vendor_commission_type',
					'vendor_commission_amount',
					'vendor_auto_approved',
					'vendor_role',
				],
				'html_keys' => [

				],
			],
			[
				'id' => 'agent',
				'title' => __("Agent Settings"),
				'position' => 50,
				'view' => "Vendor::admin.settings.agent",
				"keys" => [
					'agent_enable',
					'agent_commission_type',
					'agent_commission_amount',
					'agent_auto_approved',
					'agent_role',
				],
				'html_keys' => [

				],
			],
		];
	}
}
