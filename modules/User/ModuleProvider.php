<?php
namespace Modules\User;
use Modules\ModuleServiceProvider;
use Modules\User\Controllers\Vendors\PayoutController;

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

    public static function getUserMenu()
    {
        return [
//            'payout'=>[
//                'url'        => route('vendor.payout.index'),
//                'title'      => __("Payout"),
//                'icon'       => 'fa fa-building-o',
//                'position'   => 31,
//                'permission' => 'dashboard_vendor_access',
//            ]
        ];
    }
}
