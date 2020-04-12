<?php

namespace bachphuc\LaravelHTMLElements\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

use bachphuc\LaravelHTMLElements\Components\BaseElement;

class PackageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /*
         * Register the service provider for the dependency.
         */

        // $class = BaseElement::getModelClass(\App\User::class);
        // var_dump($class);die;
        // $config = module_namespace(__NAMESPACE__) . '\\Config';

        // $this->app->register('Modules\\' . $config::MODULE_NAME . '\\App\Providers\RouteServiceProvider');

        // echo dirname(dirname(__FILE__));die;
        /*
         * Create aliases for the dependency.
         */
        // todo: create alias
        // $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        // $loader->alias('Purifier', 'LukeTowers\Purifier\Facades\Purifier');
        // todo: register view path
        $this->loadViewsFrom(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '/resources/views', 'bachphuc.elements');
    }
}
