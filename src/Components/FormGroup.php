<?php
namespace bachphuc\LaravelHTMLElements\Components;

class FormGroup extends BaseElement
{   
    protected $viewPath = 'form-group';

    public function setChildrenAttribute($fields){
        $elements = [];
        foreach($fields as $k => $tmp){
            $element = $this->createElement($k, $tmp);
            $element->showWrap(false);  
            $elements[$element->getName()] = $element;
        }
        
        $this->setAttribute('elements', $elements);
    }

    public function setItem($item){
        $this->item = $item;
        $data = $item->toArray();
        $this->populate($data);
        foreach($this->getAttribute('elements') as $ele){
            $ele->setItem($item);
        }
    }

    public function populate($data = [], $bFromRequest = false){
        $this->data = $data;

        $elements = $this->getAttribute('elements');
        foreach($elements as &$ele){
            $name = $ele->getAttribute('name');
            if(isset($data[$name])){
                $ele->setValue($data[$name]);
            }
            else if($bFromRequest && request()->has($name)){
                $ele->setValue('');
            }
            else if($bFromRequest && $ele->getType() === 'checkbox'){
                $ele->resetDefaultValue();
            }
        }
    }

    public function getData(){
        $result = [];
        foreach($this->getAttribute('elements') as $ele){
            $name = $ele->getAttribute('name');
            $value = $ele->getValue();
            if($ele->getAttribute('leave_if_empty') && empty($value)){
                // do not pass empty value into this field, should ignore it when update
            }
            else{
                $result[$name] = $value;
            }
        }
        return $result;
    }
}
