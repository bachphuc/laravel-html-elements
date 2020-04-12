<?php
namespace bachphuc\LaravelHTMLElements\Components;

class GoogleMapAutoSuggestion extends BaseElement
{   
    protected $viewPath = 'google-map-auto-suggestion';

    public function getTitle(){
        $title = null;
        $title = $this->getAttribute('title');

        if(empty($title)){
            $title = $this->getAttribute('name');
        }

        if(!empty($title)){
            $title = str_replace('_', ' ', $title);
            $title = str_replace('-', ' ', $title);
        }

        return $title;
    }

    public function render($params = []){
        $this->setAttribute('title', title_case($this->getTitle()));
        return parent::render($params);
    }
}
