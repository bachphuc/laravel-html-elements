<?php
namespace bachphuc\LaravelHTMLElements\Components;

use bachphuc\LaravelHTMLElements\Components\TabItem;

class Tab extends ViewGroup
{   
    protected $viewPath = 'tab';

    public function setChildrenAttribute($tabs){
        $this->_childrenData = $tabs;
        $elements = [];
        $tabItems = [];

        $id = $this->getId();
        if(!empty($id)){
            $id.='-';
        }
        foreach($tabs as $k => $tmp){
            $key = is_array($tmp) ? $k : $tmp;
            $ele = is_array($tmp) ? $tmp : [
                'type' => 'tab_content'
            ];
            if(!isset($ele['type'])){
                $ele['type'] = 'tab_content';
            }
            $class = $this->getElementClass($ele['type'], isset($ele['module']) ? $ele['module'] : '');
            if(empty($class)){
                if(!isset($ele['module'])){
                    die('Missing implement App\\Http\\Components\\' . studly_case($ele['type']));
                }
                else{
                    die('Missing implement Modules\\' . ucfirst($ele['module']) . '\\App\\Http\\Components\\' . studly_case($ele['type']));
                }
            }
            $element = new $class();
            $element->setName($key);
            $ele['name'] = $key;
            $element->setTheme($this->getTheme());
            $element->setModule($this->getModule());
            $element->setIsUpdate($this->isUpdate);
            if(!isset($ele['id'])){
                $ele['id'] = $id . 'tab-' . (is_numeric($k) ? $k + 1 : $k);
            }
            if($k === 0){
                $ele['active'] = true;
            }
            else{
                $ele['active'] = false;
            }
            $element->setAttributes($ele);              
            $element->showWrap(false);  
            $elements[$key] = $element;

            $tabItem = new TabItem();
            $tabItem->setTheme($this->getTheme());
            $tabItemParams = [
                'id' => $ele['id'],
                'title' => isset($ele['title']) ? $ele['title'] : '',
                'icon' => isset($ele['icon']) ? $ele['icon'] : '',
                'active' => $ele['active'],
            ];
            $tabItem->setAttributes($tabItemParams);              

            $tabItems[] = $tabItem;
        }

        $this->setAttribute('tabItems', $tabItems);
        $this->setAttribute('elements', $elements);
    }
}
