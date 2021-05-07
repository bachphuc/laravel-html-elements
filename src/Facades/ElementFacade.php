<?php

namespace bachphuc\LaravelHTMLElements\Facades;

use Illuminate\Support\Facades\Facade;

class ElementFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'html_element'; }

    public static function tinymceRoutes($params = []){
        $path = isset($params['path']) ? $params['path'] : '/tinymce/image/upload';
        $name = isset($params['name']) ? $params['name'] : 'tinymce.image.upload';
        $router = static::$app->make('router');
        $namespace = '\bachphuc\LaravelHTMLElements\Http\Controllers\\';

        $router->post($path, $namespace . 'TinymceController@uploadImage')->name($name);
    }

}