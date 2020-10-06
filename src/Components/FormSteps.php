<?php

namespace bachphuc\LaravelHTMLElements\Components;

use bachphuc\LaravelHTMLElements\Components\Tab;

class FormSteps extends Form
{
    /**
     * $renderActionButtons
     */

    protected $viewPath = 'form-steps';

    public function setElements($data){
        return $this->setSteps($data);
    }

    public function setStepsAttribute($steps){
        $this->setSteps($steps);
    }

    public function setSteps($steps){
        $elements = [];
        $formElements = [];

        $tab = new Tab();
        $tab->setTheme($this->getTheme())
        ->setAttributes([
            'navClass' => 'nav-pills nav-pills-primary'
        ])
        ->setChildren($steps);

        $elements[] = $tab;

        foreach($tab->getChildren() as $key => $tabContent){
            foreach($tabContent->getChildren() as $key => $element){
                $name = $element->getName();
                if(!empty($name)){
                    $formElements[$name] = $element;
                }
                else if(is_string($key) && strpos($key, '->') === false){
                    $formElements[$key] = $element;
                }
                else{
                    $formElements[] = $element;
                }
            }
        }

        $this->elements = $formElements;

        
        $this->setAttribute('elements', $elements);
    }

    public function getData(){
        $result = [];
        $ignoreInputFields = [
            'html', 'typography', 'tab', 'button',
        ];
        foreach($this->elements as $ele){
            if(in_array($ele->getType(), $ignoreInputFields)){
                continue;
            }
            if($ele->getType() == 'form_group'){
                $values = $ele->getData();                
                foreach($values as $k => $name){
                    $result[$k] = $name;
                }
            }
            else{
                $name = $ele->getAttribute('name');
                $value = $ele->getValue();
                if($ele->getAttribute('leave_if_empty') && empty($value)){
                    // do not pass empty value into this field, should ignore it when update
                }
                else{
                    $result[$name] = $value;
                }
            }
        }
        return $result;
    }

    public function render($params = []){
        $params['data'] = $this->data;

        $view = $this->view($params);
        
        $content = $view->render();
        return $content;
    }
}