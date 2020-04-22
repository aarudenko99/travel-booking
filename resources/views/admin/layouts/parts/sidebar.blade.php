<?php
$menus = [
	'admin' => [
		'url' => 'admin',
		'title' => __("Dashboard"),
		'icon' => 'icon ion-ios-desktop',
		"position" => 0,
	],
	'news' => [
		"position" => 10,
		'url' => 'admin/module/news',
		'title' => __("News"),
		'icon' => 'ion-md-bookmarks',
		'permission' => 'news_view',
		'children' => [
			'news_view' => [
				'url' => 'admin/module/news',
				'title' => __("All News"),
				'permission' => 'news_view',
			],
			'news_create' => [
				'url' => 'admin/module/news/create',
				'title' => __("Add News"),
				'permission' => 'news_create',
			],
			'news_categoty' => [
				'url' => 'admin/module/news/category',
				'title' => __("Categories"),
				'permission' => 'news_create',
			],
			'news_tag' => [
				'url' => 'admin/module/news/tag',
				'title' => __("Tags"),
				'permission' => 'news_create',
			],
		],
	],
	'page' => [
		"position" => 20,
		'url' => 'admin/module/page',
		'title' => __("Page"),
		'icon' => 'icon ion-ios-bookmarks',
	],
	'location' => [
		"position" => 30,
		'url' => 'admin/module/location',
		'title' => __("Location"),
		'icon' => 'icon ion-md-compass',
		'permission' => 'location_view',
	],

	'review' => [
		"position" => 50,
		'url' => 'admin/module/review',
		'title' => __("Reviews"),
		'icon' => 'icon ion-ios-text',
	],
	'menu' => [
		"position" => 60,
		'url' => 'admin/module/core/menu',
		'title' => __("Menu"),
		'icon' => 'icon ion-ios-apps',
		'permission' => 'menu_view',
	],
	'template' => [
		"position" => 70,
		'url' => 'admin/module/template',
		'title' => __('Templates'),
		'icon' => 'icon ion-logo-html5',
		'permission' => 'template_create',
	],
	'general' => [
		"position" => 80,
		'url' => 'admin/module/core/settings/index/general',
		'title' => __('Setting'),
		'icon' => 'icon ion-ios-cog',
		'permission' => 'setting_update',
		'children' => \Modules\Core\Models\Settings::getSettingPages(),
	],
	'tools' => [
		"position" => 90,
		'url' => 'admin/module/core/tools',
		'title' => __("Tools"),
		'icon' => 'icon ion-ios-hammer',
		'children' => [
			'language' => [
				'url' => 'admin/module/language',
				'title' => __('Languages'),
				'icon' => 'icon ion-ios-globe',
				'permission' => 'language_manage',
			],
			'translations' => [
				'url' => 'admin/module/language/translations',
				'title' => __("Translation Manager"),
				'icon' => 'icon ion-ios-globe',
				'permission' => 'language_translation',
			],
			'logs' => [
				'url' => 'admin/logs',
				'title' => __("System Logs"),
				'icon' => 'icon ion-ios-nuclear',
				'permission' => 'system_log_view',
			],
		],
	],
	'users' => [
		"position" => 100,
		'url' => 'admin/module/user',
		'title' => __('Users'),
		'icon' => 'icon ion-ios-contacts',
		'permission' => 'user_view',
		'children' => [
			'user' => [
				'url' => 'admin/module/user',
				'title' => __('All Users'),
				'icon' => 'fa fa-user',
			],
			'role' => [
				'url' => 'admin/module/user/role',
				'title' => __('Role Manager'),
				'permission' => 'role_view',
				'icon' => 'fa fa-lock',
			],
//            [
			//                'url'        => 'admin/module/user/permission',
			//                'title'      => __('Permission Manager'),
			//                'permission' => 'permission_view',
			//                'icon'       => 'fa fa-lock',
			//            ],
			'subscriber' => [
				'url' => 'admin/module/user/subscriber',
				'title' => __('Subscribers'),
				'permission' => 'newsletter_manage',
			],
			'userUpgradeRequest' => [
				'url' => 'admin/module/user/userUpgradeRequest',
				'title' => __('User Upgrade Request'),
				'permission' => 'user_view',
			],
		],
	],
	'booking' => [
		"position" => 110,
		'url' => 'admin/module/report/booking',
		'title' => __('Reports'),
		'icon' => 'icon ion-ios-pie',
		'permission' => 'report_view',
		'children' => [
			'booking' => [
				'url' => 'admin/module/report/booking',
				'title' => __('Booking Reports'),
				'icon' => 'icon ion-ios-pricetags',
				'permission' => 'report_view',
			],
			'statistic' => [
				'url' => 'admin/module/report/statistic',
				'title' => __('Booking Statistic'),
				'icon' => 'icon ion ion-md-podium',
				'permission' => 'report_view',
			],
			'contact' => [
				'url' => 'admin/module/contact',
				'title' => __('Contact Submissions'),
				'icon' => 'icon ion ion-md-mail',
				'permission' => 'contact_manage',
			],

		],
	],
//    [
	//        "position"=>120,
	//        'url'        => 'admin/module/vendor/plan',
	//        'title'      => __('Vendor'),
	//        'icon'       => 'icon ion-ios-paper',
	//        'permission' => 'report_view',
	//        'children'   => [
	//            [
	//                'url'        => 'admin/module/vendor/plan',
	//                'title'      => __('Vendor Plans'),
	//                'icon'       => 'icon ion-ios-paper',
	////                'permission' => 'vendor_plan_view',
	//            ],
	//        ]
	//    ],
];

// Get All Plugins Menu
if (class_exists('\Nwidart\Modules\Facades\Module')) {
	$plugins = \Nwidart\Modules\Facades\Module::allEnabled();
	foreach ($plugins as $plugin) {
		$adminMenu = config($plugin->getLowerName() . '.admin_menu');
		if (!empty($adminMenu)) {
			$menus = array_merge($menus, $adminMenu);
		}
	}
}

// Modules
$custom_modules = \Modules\ServiceProvider::getModules();
if (!empty($custom_modules)) {
	foreach ($custom_modules as $module) {
		$moduleClass = "\\Modules\\" . ucfirst($module) . "\\ModuleProvider";
		if (class_exists($moduleClass)) {
			$menuConfig = call_user_func([$moduleClass, 'getAdminMenu']);

			if (!empty($menuConfig)) {
				$menus = array_merge($menus, $menuConfig);
			}

			$menuSubMenu = call_user_func([$moduleClass, 'getAdminSubMenu']);

			if (!empty($menuSubMenu)) {
				foreach ($menuSubMenu as $k => $submenu) {
					$submenu['id'] = $submenu['id'] ?? '_' . $k;
					if (!empty($submenu['parent']) and isset($menus[$submenu['parent']])) {
						$menus[$submenu['parent']]['children'][$submenu['id']] = $submenu;
						$menus[$submenu['parent']]['children'] = array_values(\Illuminate\Support\Arr::sort($menus[$submenu['parent']]['children'], function ($value) {
							return $value['position'] ?? 100;
						}));
					}
				}

			}
		}

	}
}

// Custom Menu
$custom_modules = \Custom\ServiceProvider::getModules();
if (!empty($custom_modules)) {
	foreach ($custom_modules as $module) {
		$moduleClass = "\\Custom\\" . ucfirst($module) . "\\ModuleProvider";
		if (class_exists($moduleClass)) {
			$menuConfig = call_user_func([$moduleClass, 'getAdminMenu']);

			if (!empty($menuConfig)) {
				$menus = array_merge($menus, $menuConfig);
			}

			$menuSubMenu = call_user_func([$moduleClass, 'getAdminSubMenu']);

			if (!empty($menuSubMenu)) {
				foreach ($menuSubMenu as $k => $submenu) {
					$submenu['id'] = $submenu['id'] ?? '_' . $k;
					if (!empty($submenu['parent']) and isset($menus[$submenu['parent']])) {
						$menus[$submenu['parent']]['children'][$submenu['id']] = $submenu;
						$menus[$submenu['parent']]['children'] = array_values(\Illuminate\Support\Arr::sort($menus[$submenu['parent']]['children'], function ($value) {
							return $value['position'] ?? 100;
						}));
					}
				}

			}
		}

	}
}

$currentUrl = url(\Modules\Core\Walkers\MenuWalker::getActiveMenu());
$user = \Illuminate\Support\Facades\Auth::user();
if (!empty($menus)) {
	foreach ($menus as $k => $menuItem) {

		if (!empty($menuItem['permission']) and !$user->hasPermissionTo($menuItem['permission'])) {
			unset($menus[$k]);
			continue;
		}
		$menus[$k]['class'] = $currentUrl == url($menuItem['url']) ? 'active' : '';
		if (!empty($menuItem['children'])) {
			$menus[$k]['class'] .= ' has-children';
			foreach ($menuItem['children'] as $k2 => $menuItem2) {
				if (!empty($menuItem2['permission']) and !$user->hasPermissionTo($menuItem2['permission'])) {
					unset($menus[$k]['children'][$k2]);
					continue;
				}
				$menus[$k]['children'][$k2]['class'] = $currentUrl == url($menuItem2['url']) ? 'active' : '';
			}
		}
	}

	//@todo Sort Menu by Position
	$menus = array_values(\Illuminate\Support\Arr::sort($menus, function ($value) {
		return $value['position'] ?? 100;
	}));
}
$isAdmin = Auth::user()->hasRole('administrator');
$isCustomer = Auth::user()->hasRole('customer');
// dd($isCustomer);
?>

<ul class="main-menu">
    @foreach($menus as $menuItem)
        <li {{((!$isAdmin && $menuItem['position'] == 20)|| (!$isAdmin && $menuItem['position'] == 50) || (!$isAdmin && $menuItem['position'] == 90)) ? 'hidden' : ''}} class="{{$menuItem['class']}}"><a href="{{ url($menuItem['url']) }}">
            <!-- {{$menuItem['position']}} {{($isAdmin) ? 'true' : 'false'}} -->

                @if(!empty($menuItem['icon']))
                    <span class="icon text-center"><i class="{{$menuItem['icon']}}"></i></span>
                @endif
                {{$menuItem['title']}}
            </a>

            @if(!empty($menuItem['children']))
                <span class="btn-toggle"><i class="fa fa-angle-left pull-right"></i></span>
                <ul class="children">
                    @foreach($menuItem['children'] as $menuItem2)

                            <li class="{{$menuItem['class']}}" {{ ($isCustomer && $menuItem2['url'] == 'admin/module/report/statistic') ? 'hidden' : '' }}>
                                <a href="{{ url($menuItem2['url']) }}">
                                    @if(!empty($menuItem2['icon']))
                                        <i class="{{$menuItem2['icon']}}"></i>
                                    @endif
                                    {{$menuItem2['title']}}
                                </a>
                            </li>
                    @endforeach
                </ul>
            @endif

        </li>

    @endforeach
</ul>
