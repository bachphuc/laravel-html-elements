<?php
namespace bachphuc\LaravelHTMLElements\Components;

class TextEditor extends BaseElement
{   
    protected $viewPath = 'text-editor';
    protected $requiredAssetViewPath = 'text-editor-asset';

    public function getTitle(){
        $title = $this->getAttribute('title');
        if(!empty($title)){
            return $title;
        }
        $title = $this->getAttribute('name');
        if(!empty($title)){
            return $title;
        }
        return null;
    }

    public function render($params = []){
        $this->setAttribute('title', title_case($this->getTitle()));
        return parent::render($params);
    }

    public static function assetViewPath(){
        return 'elements::assets.tinymce';
    }
}
