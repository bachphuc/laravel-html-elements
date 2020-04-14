<?php
namespace bachphuc\LaravelHTMLElements\Components;

class Gallery extends BaseElement
{   
    protected $viewPath = 'gallery';

    public function getTitle(){
        $title = $this->getAttribute('title');
        if(!empty($title)){
            return $title;
        }
        $title = $this->getAttribute('name');
        if(!empty($title)){
            return $title;
        }
        return null;
    }

    public function render($params = []){
        $this->setAttribute('title', title_case($this->getTitle()));
        return parent::render($params);
    }
}
