<?php
namespace bachphuc\LaravelHTMLElements\Components;

class Typography extends BaseElement
{   
    protected $viewPath = 'typography';

    protected $defaultAttributes = [
        'class' => '',
        'id' => ''
    ];

    public function setTextAttribute($text){
        
    }

    public function render($params = []){
        if(!$this->hasAttribute('tag')){
            $this->setAttribute('tag', 'p');
        }
        return parent::render($params);
    }
}
