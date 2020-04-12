<?php
namespace bachphuc\LaravelHTMLElements\Components;

use bachphuc\LaravelHTMLElements\Models\ModelBase;

class SelectMulti extends BaseElement
{
    protected $viewPath = 'select-multi';

    public function setOptionsAttribute($options){
        if(isset($options['model']) && !empty($options['model'])){
            $class = ModelBase::getModelClass($options['model']);
            if(!empty($class)){
                $items = $class::all();
                $this->setAttribute('items', $items);
                $this->setAttribute('dataType', 'model');
            }
        }
        else if(isset($options['data']) && !empty($options['data'])){
            $this->setAttribute('items', $options['data']);
            $this->setAttribute('dataType', 'array');
        }
    }

    public function prepareProcess(&$item = null, &$data = []){
        $name = $this->getAttribute('name');
        if(!isset($data[$name])){
            $data[$name] = '';
        }
        $value = $data[$name];
        if(is_array($value)){
            $data[$name] = implode(',', $value);
        }
    }

    public function setValueAttribute($value){
        if(is_string($value)){
            $value = explode(',', $value);
        }
        $this->attributes['value'] = $value;
    }
}
