<?php
namespace bachphuc\LaravelHTMLElements\Components;

class Button extends BaseElement
{   
    protected $viewPath = 'button';

    protected $defaultAttributes = [
        'tag' => 'button',
        'type' => 'button',
        'title' => '',
        'class' => '',
        'href' => ''
    ];
}
