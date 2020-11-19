<?php

namespace bachphuc\LaravelHTMLElements\Components;

use bachphuc\LaravelHTMLElements\Facades\ElementFacade as Element;

class BaseElement
{
    const VIEW_BASE_PATH = 'bachphuc.elements';
    // module
    protected $baseViewPath = 'bachphuc.elements';
    // folder path
    protected $folderPath = '';
    // view name
    protected $viewPath = 'base';
    protected $fullViewPath = '';
    protected $attributes = [];
    protected $item = null;
    protected $theme = 'default';
    protected $module = '';
    protected $name = '';
    protected $_data = null;

    protected $_type = null;

    protected $isUpdate = false;

    protected $defaultAttributes = [];
    protected $viewData = [];

    function __construct() {
        
    }

    public function getType(){
        return $this->_type;
    }

    public function getId(){
        return $this->getAttribute('id');
    }

    public function setIsUpdate($v){
        $this->isUpdate = $v;
        return $this;
    }

    public function setTheme($t){
        $this->theme = $t;
        $elements = $this->getAttribute('elements', []);
        foreach($elements as $element){
            $element->setTheme($this->theme);
        }
        return $this;
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

    public function showWrap($b){
        $this->setAttribute('show_wrap', $b);
    }

    public function render($params = []){
        $data = $this->run($params);
        if(!empty($data)){
            $this->setAttributes($data);
        }

        $view = $this->view($params);
        
        $content = $view->render();
        return $content;
    }

    public function run($params = []){
        return [];
    }

    public function view($params = []){        
        $data = array_merge($this->defaultAttributes, $this->attributes, $params);
        if(!empty($this->item) && !isset($data['item'])){
            $data['item'] = $this->item;
        }
        $data['self'] = $this;
        $data['theme'] = $this->theme;
        $data['showWrap'] = $this->getAttribute('show_wrap', true);

        $this->viewData = $data;

        return view($this->getViewPath(), $this->viewData);
    }

    public static function getElementClass($model, $module = ''){
        if(class_exists($model)){
            return $model;
        }
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
        if(class_exists($modelClass)){
            return $modelClass;
        }

        $modelClass = Element::parse($model);
        if($modelClass) return $modelClass;

        return false;
    }

    public static function make($type, $attributes = []){
        $class = self::getElementClass($type);
        if(!$class) return null;

        $ele = new $class();

        if(!empty($attributes)){
            $ele->setAttributes($attributes);
        }

        return $ele;
    }

    public function setViewPath($path){
        $this->viewPath = $path;
        return $this;
    }

    public function getViewPath(){
        if(!empty($this->fullViewPath)){
            return $this->fullViewPath;
        }
        if(!empty($this->module)){
            return $this->module . '::elements.'. $this->theme . '.' . $this->viewPath;
        }
        if(!empty($this->folderPath)){
            return $this->baseViewPath. '::' . $this->folderPath . '.'  . $this->theme. '.elements'  . '.' . $this->viewPath;
        }
        return $this->baseViewPath. '::'. $this->theme. '.elements'  . '.' . $this->viewPath;
    }

    public function setFullViewPath($path){
        $this->fullViewPath = $path;
        return $this;
    }

    public function setAttribute($key, $value){
        $this->attributes[$key] = $value;
        $method = 'set'. title_case($key) . 'Attribute';
        if(method_exists($this, $method)){
            $this->{$method}($value);
        }
    }

    public function setAttributes($data){
        $this->_data = $data;
        $except = ['type'];
        
        if(isset($data['type'])){
            $this->_type = $data['type'];
        }
 
        if($this->isUpdate && isset($data['validator_update'])){
            $data['validator'] = $data['validator_update'];
        }
        
        foreach($data as $key => $value){
            if(!in_array($key, $except)){
                $this->setAttribute($key, $value);
            }
        }

        return $this;
    }

    public function setValue($value){
        $this->setAttribute('value', $value);

        return $this;
    }

    public function getValue(){
        $value = $this->getAttribute('value');
        if(!empty($value) && is_string($value)){
            $encode = $this->getAttribute('encode');
            if($encode === 'bcrypt'){
                return bcrypt($value);
            }
        }
        
        return $value;
    }

    public function setValidatorAttribute($value){
        if(!empty($value)){
            $rules = explode('|', $value);
            $tmp = [];
            foreach($rules as $rule){
                if(strpos($rule, ':') === false){
                    $tmp[$rule] = $rule;
                }
                else{
                    $parts = explode(':', $rule);
                    $tmp[$parts[0]] = $parts[1];
                }
            }
            
            $this->setAttribute('validators', $tmp);
        }
    }

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function hasAttribute($key){
        return isset($this->attributes[$key]) ? true : false;
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

    public function resetDefaultValue(){
        
    }

    public function parse($str){
        // {type}->attribute:value,
        // typography->tag:h1;text:hello

        if(strpos($str, '->') === false) return null;
        $tmp = explode('->', $str);
        $type = $tmp[0];
        $strAttributes = $tmp[1];
        
        $class = self::getElementClass($type);
        if(!$class) return null;

        $results = [
            'class' => $class,
        ];

        $attributes = [
            'type' => $type
        ];
        if(!empty($strAttributes)){
            $tmp1 = explode(';', $strAttributes);
            foreach($tmp1 as $strAttribute){
                $tmp2 = explode(':', $strAttribute);
                $k = trim(array_shift($tmp2));
                $v = trim(implode(':', $tmp2));
                if($v === 'true'){
                    $v = true;
                }
                else if($v === 'false'){
                    $v = false;
                }
                $attributes[$k] = $v;
            }
        }
        
        $results['attributes'] = $attributes;

        return $results;
    }

    public function createElement($key, $data, $params = []){
        $class = null;
        $ele = [];
        $name = '';
        $defaultElement = 'text';
        if(isset($params['default'])){
            $defaultElement = $params['default'];
        }

        // container->attributes => children
        if(is_string($key) && strpos($key, '->') !== false){
            $elementInfo = $this->parse($key);
            if($elementInfo){
                $class = $elementInfo['class'];
                $ele = $elementInfo['attributes'];
                if(!empty($data) && is_array($data)){
                    $ele['children'] = $data;
                }
                
                if(isset($ele['name'])){
                    $name = $ele['name'];
                }
                $stop = true;
            }
        }

        if(!$class){
            if(is_array($data)){
                $name = $key;
                if(isset($data['name']) && !empty($data['name'])){
                    $name = $data['name'];
                }
                $ele = $data;
                if(isset($data['com']) && !empty($data['com'])){
                    $elementInfo = $this->parse($data['com']);
                    if($elementInfo){
                        unset($data['com']);
                        $class = $elementInfo['class'];
                        $ele = array_merge($elementInfo['attributes'], $data);
                    }
                }
            }
            else{
                $elementInfo = $this->parse($data);
                if($elementInfo){
                    $class = $elementInfo['class'];
                    $ele = $elementInfo['attributes'];
                }
                else{
                    $name = $data;
                    // default component type is text
                    $ele = [
                        'type' => $defaultElement
                    ];
                }
            }
        }

        // set default input type text
        if(!isset($ele['type'])){
            $ele['type'] = $defaultElement;
        }

        if(empty($class)){
            $class = self::getElementClass($ele['type'], isset($ele['module']) ? $ele['module'] : '');
        }
        
        if(empty($class)){
            if(!isset($ele['module'])){
                die('Missing implement App\\Http\\Components\\' . studly_case($ele['type']));
            }
            else{
                die('Missing implement Modules\\' . ucfirst($ele['module']) . '\\App\\Http\\Components\\' . studly_case($ele['type']));
            }
        }
        $element = new $class();
        $element->setName($name);
        $ele['name'] = $name;
        $element->setTheme($this->getTheme());
        $element->setModule($this->getModule());
        $element->setIsUpdate($this->isUpdate);
        $element->setAttributes($ele);              

        return $element;
    }

    public function getChildren(){
        return $this->getAttribute('elements', []);
    }
}
