<?php
namespace Modules\Cruise;

use Illuminate\Support\ServiceProvider;
use Modules\ModuleServiceProvider;
use Modules\Cruise\Models\Cruise;
use Modules\Cruise\Providers\RouterServiceProvider;

class ModuleProvider extends ModuleServiceProvider
{
    public function boot()
    {

        $this->loadMigrationsFrom(__DIR__ . '/Migrations');
        $this->publishes([
            __DIR__ . '/Config/config.php' => config_path('location.php'),
        ]);
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/config.php', 'cruise');
        $this->app->register(RouterServiceProvider::class);
    }

    public static function getBookableServices()
    {
        return [
            'cruise' => Cruise::class,
        ];
    }


    public static function getAdminMenu()
    {
        return [
            'cruise'=>[
                "position"=>40,
                'url'        => 'admin/module/cruise',
                'title'      => __("Cruise"),
                'icon'       => 'icon ion-md-umbrella',
                'permission' => 'cruise_view',
                'children'   => [
                    'cruise_view'=>[
                        'url'        => 'admin/module/cruise',
                        'title'      => __('All Cruises'),
                        'permission' => 'cruise_view',
                    ],
                    'cruise_create'=>[
                        'url'        => 'admin/module/cruise/create',
                        'title'      => __("Add Cruise"),
                        'permission' => 'cruise_create',
                    ],
                    'cruise_category'=>[
                        'url'        => 'admin/module/cruise/category',
                        'title'      => __('Categories'),
                        'permission' => 'cruise_manage_others',
                    ],
                    'cruise_attribute'=>[
                        'url'        => 'admin/module/cruise/attribute',
                        'title'      => __('Attributes'),
                        'permission' => 'cruise_manage_attributes',
                    ],
                    'cruise_availability'=>[
                        'url'        => 'admin/module/cruise/availability',
                        'title'      => __('Availability'),
                        'permission' => 'cruise_create',
                    ],
                    'cruise_booking'=>[
                        'url'        => 'admin/module/cruise/booking',
                        'title'      => __('Booking Calendar'),
                        'permission' => 'cruise_create',
                    ],
                ]
            ],
        ];
    }


    public static function getUserMenu()
    {
        return [
            'cruise' => [
                'url'        => app_get_locale() . '/user/cruise',
                'title'      => __("Manage Cruise"),
                'icon'       => 'icon ion-md-umbrella',
                'permission' => 'cruise_view',
                'position'   => 30,
                'children'   => [
                    [
                        'url'   => app_get_locale() . '/user/cruise',
                        'title' => "All Cruises",
                    ],
                    [
                        'url'        => app_get_locale() . '/user/cruise/create',
                        'title'      => "Add Cruise",
                        'permission' => 'cruise_create',
                    ],
                    [
                        'url'        => route('cruise.vendor.availability.index'),
                        'title'      => __("Availability"),
                        'permission' => 'space_create',
                    ],
                    [
                        'url'        => app_get_locale() . '/user/cruise/booking-report',
                        'title'      => "Booking Report",
                        'permission' => 'cruise_view',
                    ],
                ]
            ],
        ];
    }
}
