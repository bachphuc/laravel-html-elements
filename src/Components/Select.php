<?php
namespace bachphuc\LaravelHTMLElements\Components;

/**
 * type : select
 * options: 
 * - model: Model class
 * - data: array key => value
 */
class Select extends BaseElement
{
    protected $viewPath = 'select';

    public function setOptionsAttribute($options){
        if(isset($options['model']) && !empty($options['model'])){
            $class = BaseElement::getModelClass($options['model']);
            if(!empty($class)){
                if(!isset($options['conditions']) || empty($options['conditions'])){
                    $items = $class::all();
                }
                else{
                    $query = null;
                    foreach($options['conditions'] as $key => $v){
                        if(!$query){
                            $query = $class::where($key, $v);
                        }
                        else{
                            $query->where($key, $v);
                        }

                        $items = $query->get();
                    }
                }
                $this->setAttribute('items', $items);
                $this->setAttribute('dataType', 'model');
            }
        }
        else if(isset($options['data']) && !empty($options['data'])){
            $this->setAttribute('items', $options['data']);
            $this->setAttribute('dataType', 'array');
        }
    }
}
