<?php

namespace bachphuc\LaravelHTMLElements;

use bachphuc\LaravelHTMLElements\Components\BaseElement;

class Element
{
    protected $theme = 'default';
    protected $_classMaps = [];
    protected $_namespaceMaps = [];

    public function map($key, $class){
        $this->_classMaps[$key] = $class;
        return $this;
    }

    public function has($key){
        return isset($this->_classMaps[$key]) ? true : false;
    }

    public function getMap($key){
        if($this->has($key)) return $this->_classMaps[$key];
        return null;
    }

    public function mapNamespace($key, $namespace){
        $this->_namespaceMaps[$key] = $namespace;
        return $this;
    }

    public function hasNamespace($key){
        return isset($this->_namespaceMaps[$key]) ? true : false;
    }

    public function getNamespace($key){
        return $this->hasNamespace($key) ? $this->_namespaceMaps[$key] : null;
    }

    public function setTheme($theme){
        $this->theme = $theme;
        return $this;
    }

    public function getTheme($theme){
        return $this->theme;
    }

    public function make($type, $params = []){
        $ele = BaseElement::make($type, $params);

        if($ele) {
            return $ele->render();
        }
        return '<h1>'. $type . ' is not implemented.</h1>';
    }

    public function version(){
        return '1.0';
    }

    public function parse($model){
        if($this->has($model)){
            $modelClass = $this->getMap($model);

            if(class_exists($modelClass)){
                return $modelClass;
            }
        }

        if(strpos($model, '::') !== false){
            $parts = explode('::', $model);
            if($this->hasNamespace($parts[0])){
                $namespace = $this->getNamespace($parts[0]);

                $modelClass = $namespace . '\\' . studly_case($parts[1]);
                if(class_exists($modelClass)){
                    return $modelClass;
                }

                $modelClass = $namespace . '\\Http\\Components\\' . studly_case($parts[1]);
                if(class_exists($modelClass)){
                    $this->map($model, $modelClass);
                    return $modelClass;
                }

                $modelClass = $namespace . '\\Http\\Elements\\' . studly_case($parts[1]);
                if(class_exists($modelClass)){
                    $this->map($model, $modelClass);
                    return $modelClass;
                }
            }
        }

        return false;
    }
}