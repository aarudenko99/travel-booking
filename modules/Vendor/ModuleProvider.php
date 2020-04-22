<?php
namespace Modules\Vendor;

use Illuminate\Support\ServiceProvider;
use Modules\ModuleServiceProvider;
use Modules\Vendor\Providers\RouterServiceProvider;

class ModuleProvider extends ModuleServiceProvider
{

    public function boot(){
        $this->loadMigrationsFrom(__DIR__ . '/Migrations');

        $this->publishes([
            __DIR__.'/Config/config.php' => config_path('location.php'),
        ]);

    }
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/Config/config.php', 'vendor'
        );
        $this->app->register(RouterServiceProvider::class);

    }
}
