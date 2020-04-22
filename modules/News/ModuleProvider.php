<?php
/**
 * Created by PhpStorm.
 * User: h2 gaming
 * Date: 7/3/2019
 * Time: 9:27 PM
 */
namespace Modules\News;

use Illuminate\Support\ServiceProvider;
use Modules\ModuleServiceProvider;
use Modules\News\Providers\RouteServiceProvider;

class ModuleProvider extends ModuleServiceProvider
{

    public function boot(){

        $this->publishes([
            __DIR__.'/Config/config.php' => config_path('news.php'),
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
            __DIR__.'/Config/config.php', 'news'
        );

        $this->app->register(RouteServiceProvider::class);
    }
}