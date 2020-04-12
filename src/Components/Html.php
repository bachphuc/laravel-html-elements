<?php
namespace bachphuc\LaravelHTMLElements\Components;

class Html extends BaseElement
{   
    protected $viewPath = 'html';

    public function render($params = []){
        $data = array_merge($this->attributes, $params);
        if(!empty($this->item) && !isset($data['item'])){
            $data['item'] = $this->item;
        }
        $view = view($this->getViewPath(), $data);
        
        $content = $view->render();
        return $content;
    }
}
