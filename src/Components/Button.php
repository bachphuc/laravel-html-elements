<?php
namespace bachphuc\LaravelHTMLElements\Components;

class Button extends BaseElement
{   
    protected $viewPath = 'button';

    protected $defaultAttributes = [
        'tag' => 'button',
        'button_type' => 'button',
        'title' => '',
        'class' => '',
        'href' => '',
        'attributesText' => '',
    ];

    public function render($params = []){
        $attributes = $this->getAttribute('attributes');
        if(!empty($attributes)){
            $tmp = [];
            foreach($attributes as $key => $v){
                $tmp[] = $key . '="' . $v . '"';
            }
            $this->setAttribute('attributesText', implode(' ', $tmp));
        }
        return parent::render($params);
    }
}
