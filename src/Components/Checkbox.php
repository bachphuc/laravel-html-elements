<?php
namespace bachphuc\LaravelHTMLElements\Components;

class Checkbox extends BaseElement
{
    protected $viewPath = 'checkbox';

    public function prepareProcess(&$item = null, &$data = []){
        $name = $this->getAttribute('name');
        $uncheckValue = $this->getAttribute('uncheck_value');
        if(empty($uncheckValue)){
            $uncheckValue = 0;
        }
        if(!isset($data[$name])){
            $data[$name] = $uncheckValue;
        }
    }

    public function resetDefaultValue(){
        $uncheckValue = $this->getAttribute('uncheck_value', 0);
        $this->setValue($uncheckValue);
    }
}
