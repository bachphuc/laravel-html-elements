<?php
namespace bachphuc\LaravelHTMLElements\Components;
use bachphuc\LaravelHTMLElements\Models\ModelBase;

class SingleTag extends BaseElement
{   
    protected $viewPath = 'single-tag';

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
        $value = $this->getAttribute('value');
        $options = $this->getAttribute('options');
        $class = ModelBase::getModelClass($options['model']);

        if(!is_numeric($value)){
            $name = $this->getAttribute('name');
            // need to create new object
            $titleField = isset($options['title']) ? $options['title'] : 'title';
            $p = [
                $titleField => trim($value)
            ];
            if(isset($options['alias']) && $options['alias']){
                $p['alias'] = str_slug($value, '-');
            }
            if(isset($options['user']) && $options['user']){
                $p['user_id'] = auth()->user()->id;
            }

            $newItem = $class::create($p);
            $data[$name] = $newItem->id;
        }
    }
}
