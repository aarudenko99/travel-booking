<?php
namespace Modules\Space;
use Modules\ModuleServiceProvider;
use Modules\Space\Models\Space;

class ModuleProvider extends ModuleServiceProvider
{

    public function boot(){

        $this->loadMigrationsFrom(__DIR__ . '/Migrations');

    }
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouterServiceProvider::class);
    }

    public static function getAdminMenu()
    {
        return [
            'space'=>[
                "position"=>41,
                'url'        => 'admin/module/space',
                'title'      => __('Space'),
                'icon'       => 'fa fa-building-o',
                'permission' => 'space_view',
                'children'   => [
                    'add'=>[
                        'url'        => 'admin/module/space',
                        'title'      => __('All Spaces'),
                        'permission' => 'space_view',
                    ],
                    'create'=>[
                        'url'        => 'admin/module/space/create',
                        'title'      => __('Add new Space'),
                        'permission' => 'space_create',
                    ],
                    'attribute'=>[
                        'url'        => 'admin/module/space/attribute',
                        'title'      => __('Attributes'),
                        'permission' => 'space_manage_attributes',
                    ],
                    'availability'=>[
                        'url'        => 'admin/module/space/availability',
                        'title'      => __('Availability'),
                        'permission' => 'space_create',
                    ],

                ]
            ]
        ];
    }

    public static function getBookableServices()
    {
        return [
            'space'=>Space::class
        ];
    }

    public static function getMenuBuilderTypes()
    {
        return [
            'space'=>[
                'class' => Space::class,
                'name'  => __("Spaces"),
                'items' => Space::searchForMenu(),
                'position'=>41
            ]
        ];
    }


    public static function getUserMenu()
    {
        return [
            'space' => [
                'url'        => app_get_locale() . '/user/space',
                'title'      => __("Manage Space"),
                'icon'       => 'fa fa-building-o',
                'position'   => 31,
                'permission' => 'space_view',
                'children' => [
                    [
                        'url'    => app_get_locale() . '/user/space',
                        'title'  => __("All Spaces"),
                    ],
                    [
                        'url'        => app_get_locale() . '/user/space/create',
                        'title'      => __("Add Space"),
                        'permission' => 'space_create',
                    ],
                    [
                        'url'        => route('space.vendor.availability.index'),
                        'title'      => __("Availability"),
                        'permission' => 'space_create',
                    ],
                    [
                        'url'        => app_get_locale() . '/user/space/booking-report',
                        'title'      => __("Booking Report"),
                        'permission' => 'space_view',
                    ],
                ]
            ],
        ];
    }
}