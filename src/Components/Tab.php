<?php
namespace bachphuc\LaravelHTMLElements\Components;

use bachphuc\LaravelHTMLElements\Components\TabItem;

class Tab extends ViewGroup
{   
    protected $viewPath = 'tab';
    protected $defaultAttributes = [
        // nav-pills nav-pills-primary
        'navClass' => 'nav-tabs',
    ];

    public function setChildrenAttribute($tabs){
        $this->_childrenData = $tabs;
        $elements = [];
        $tabItems = [];

        $id = $this->getId();
        if(!empty($id)){
            $id.='-';
        }
        $i = 0;
        foreach($tabs as $k => $tmp){
            if(strpos($k, ':') !== false){
                $k = 'tab_content->'. $k;
            }
            $element = $this->createElement($k, $tmp, [
                'default' => 'tab_content'
            ]);

            $name = $element->getName();
            if(!$element->getId()){
                $element->setAttribute('id', $id . 'tab-' . (!empty($name) && is_string($name) ? $name : ($i + 1)) );
            }
            $element->setAttribute('active', $i === 0 ? true : false);
            
            if(!empty($name)){
                $elements[$name] = $element;
            }
            else{
                $elements[] = $element;
            }

            $tabItem = new TabItem();
            $tabItem->setTheme($this->getTheme());

            $tabItemParams = [
                'id' => $element->getId(),
                'title' => $element->getAttribute('title'),
                'icon' => $element->getAttribute('icon'),
                'active' => $element->getAttribute('active'),
            ];

            $tabItem->setAttributes($tabItemParams);              

            $tabItems[] = $tabItem;

            $i++;
        }

        $this->setAttribute('tabItems', $tabItems);
        $this->setAttribute('elements', $elements);
    }
}
