<?php
namespace bachphuc\LaravelHTMLElements\Components;

class ViewGroup extends BaseElement
{   
    protected $viewPath = 'view-group';

    protected $_childrenData = null;

    public function setChildren($fields){
        return $this->setChildrenAttribute($fields);
    }

    public function setChildrenAttribute($fields){
        $this->_childrenData = $fields;

        $elements = [];
        foreach($fields as $k => $tmp){
            $key = is_array($tmp) ? $k : $tmp;
            $ele = is_array($tmp) ? $tmp : [
                'type' => 'text'
            ];

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
            $element->setAttributes($ele);              
            $element->showWrap(false);  
            $elements[$key] = $element;
        }
        
        $this->setAttribute('elements', $elements);
    }
}
