<?php

namespace bachphuc\LaravelHTMLElements\Components;

class BaseElement
{
    const VIEW_BASE_PATH = 'bachphuc.elements';
    protected $viewPath = 'base';
    protected $fullViewPath = '';
    protected $attributes = [];
    protected $item = null;
    protected $theme = 'default';
    protected $module = '';
    protected $name = '';

    public function setTheme($t){
        $this->theme = $t;
    }

    public function setModule($t){
        $this->module = $t;
    }

    public function getModule(){
        return $this->module;
    }

    public function getTheme(){
        return $this->theme;
    }

    public function render($params = []){
        $data = array_merge($this->attributes, $params);
        if(!empty($this->item) && !isset($data['item'])){
            $data['item'] = $this->item;
        }
        $view = view($this->getViewPath(), $data);
        
        $content = $view->render();
        return $content;
    }

    public function getElementClass($model, $module = ''){
        if(empty($module)){
            $modelClass = 'bachphuc\\LaravelHTMLElements\\Components\\' . studly_case($model);
            if(class_exists($modelClass)){
                return $modelClass;
            }
            $modelClass = 'App\\Http\\Components\\' . studly_case($model);
        }
        else{
            $modelClass = 'Modules\\' . ucfirst($module) . '\\App\\Http\\Components\\' . studly_case($model);
        }
        if(!class_exists($modelClass)){
            return false;
        }
        return $modelClass;
    }

    public function setViewPath($path){
        $this->viewPath = $path;
    }

    public function getViewPath(){
        if(!empty($this->fullViewPath)){
            return $this->fullViewPath;
        }
        if(!empty($this->module)){
            return $this->module . '::elements.'. $this->theme . '.' . $this->viewPath;
        }
        return BaseElement::VIEW_BASE_PATH. '::elements.'. $this->theme . '.' . $this->viewPath;
    }

    public function setAttribute($key, $value){
        $this->attributes[$key] = $value;
        $method = 'set'. title_case($key) . 'Attribute';
        if(method_exists($this, $method)){
            $this->{$method}($value);
        }
    }

    public function setAttributes($data){
        $except = ['type'];
        foreach($data as $key => $value){
            if(!in_array($key, $except)){
                $this->setAttribute($key, $value);
            }
        }
    }

    public function setValidatorAttribute($value){
        if(!empty($value)){
            $rules = explode('|', $value);
            $tmps = [];
            foreach($rules as $rule){
                if(strpos($rule, ':') === false){
                    $tmps[$rule] = $rule;
                }
                else{
                    $parts = explode(':', $rule);
                    $tmps[$parts[0]] = $parts[1];
                }
            }
            
            $this->setAttribute('validators', $tmps);
        }
    }

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function getAttribute($key, $default = null){
        if(isset($this->attributes[$key])){
            return $this->attributes[$key];
        }
        return $default;
    }

    public function process($item = null, $data = []){
        
    }

    public function setItem($item){
        $this->item = $item;
    }

    public function prepareProcess(&$item = null, &$data = []){
        
    }

    public static function getModelClass($class){
        return model_class($class);
    }
}
