<?php
namespace bachphuc\LaravelHTMLElements\Components;

use bachphuc\LaravelHTMLElements\Models\ModelBase;
use bachphuc\LaravelHTMLElements\Components\BaseElement;

class Table extends BaseElement
{
    protected $viewPath = 'table';
    protected $urlResolve = null;
    protected $modelRouteName = '';

    public function setData($items){
        $this->setAttribute('items', $items);
    }

    public function setFields($fields){
        $this->setAttribute('fields', $fields);
    }

    public function showActionButtons($b){
        $this->setAttribute('isShowActionButtons', $b);
    }

    public function setCustomAction($customActions){
        $this->setAttribute('customActions', $customActions);
    }

    public function setRenderRowAttribute($rowAttributeRender){
        $this->setAttribute('rowAttributeRender', $rowAttributeRender);
    }
    public function showPaginator($b){
        $this->setAttribute('isShowPaginator', $b);
    }

    public function setCanShowValidator($c){
        $this->setAttribute('canShowValidator', $c);
    }

    public function setItemUrlResolve($resolve){
        $this->urlResolve = $resolve;   
    }

    public function handleUrl($item, $action = '', $params = []){
        if($this->urlResolve){
            $resolve = $this->urlResolve;
            return $resolve($item, $action, $params);
        }

        if(!empty($this->modelRouteName)){
            $route = $this->modelRouteName . '.' . $action;
            if(\Route::has($route)){
                if(empty($params)){
                    $routeParams = $this->getAttribute('route_params');
                    if(!empty($routeParams)){
                        $params = $routeParams;
                    }
                }
                $params['id'] = $item->id;
                return route($route, $params);
            }
        }

        return url('');
    }

    public function render($params = []){
        return parent::render($params);
    }

    public function getPaginateView(){
        return BaseElement::VIEW_BASE_PATH . '::' . $this->theme . '.manage.base.paginate';
    }

    public static function create($params = []){
        $table = new Table();
        if(isset($params['items'])){
            $table->setData($params['items']);
        }
        if(isset($params['models'])){
            $table->setAttribute('models' , $params['models']);
        }

        if(isset($params['item_title'])){
            $table->setAttribute('item_title' , $params['item_title']);
        }

        if(isset($params['route_params'])){
            $table->setAttribute('route_params' , $params['route_params']);
        }

        if(isset($params['fields'])){
            $table->setFields($params['fields']);
        }

        if(isset($params['model_route_name'])){
            $table->setModelRouteName($params['model_route_name']);
        }
        
        $table->showActionButtons(isset($params['show_action_buttons']) ? $params['show_action_buttons'] : false);
       
        if(isset($params['theme'])){
            $table->setTheme($params['theme']);
        }
        $table->showPaginator(isset($params['show_paginate']) ? $params['show_paginate'] : false);
        if(isset($params['params'])){
            $table->setAttribute('params' , $params['params']);
        }

        return $table;
    }

    public function setModelRouteName($name){
        $this->modelRouteName = $name;
    }

    public function setModel_Route_NameAttribute($name){
        $this->setModelRouteName($name);
    }
}
