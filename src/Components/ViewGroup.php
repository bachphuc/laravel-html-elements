<?php
namespace bachphuc\LaravelHTMLElements\Components;

class ViewGroup extends BaseElement
{   
    protected $viewPath = 'view-group';

    protected $_childrenData = null;

    function __construct() {
        $numargs = func_num_args();
        if(!$numargs) return;

        $arg_list = func_get_args();
        $this->load($arg_list);
    }

    function load($args){
        $elements = [];
        foreach($args as $arg){
            if($arg && $arg instanceof BaseElement){
                $elements[] = $arg;
            }
        }

        if(!empty($elements)){
            $this->setAttribute('elements', $elements);
        }
    }

    public function setChildren($fields){
        return $this->setChildrenAttribute($fields);
    }

    public function setChildrenAttribute($fields){
        $this->_childrenData = $fields;

        $elements = [];
        foreach($fields as $k => $tmp){
            if($tmp instanceof BaseElement){
                $element = $tmp;
            }
            else{
                $element = $this->createElement($k, $tmp);
            }
            
            $name = $element->getName();
            if(empty($name) && !empty($k) && is_string($k) && strpos($k, '->') === false){
                $name = $k;
            }
            if(!empty($name)){
                $elements[$name] = $element;
            }
            else{
                $elements[] = $element;
            }
        }
        
        $this->setAttribute('elements', $elements);
    }
}
