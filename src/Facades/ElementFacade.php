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

}