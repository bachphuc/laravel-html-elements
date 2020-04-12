<?php
namespace bachphuc\LaravelHTMLElements\Components;

use bachphuc\LaravelHTMLElements\Models\ModelBase;

class Table extends BaseElement
{
    protected $viewPath = 'table';

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
}
