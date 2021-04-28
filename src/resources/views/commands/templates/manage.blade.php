@php
    $tag = '<?php';
@endphp
{!! $tag !!}
namespace {{$namespace}};

use bachphuc\LaravelHTMLElements\Http\Controllers\ManageBaseController;

class {{$className}} extends ManageBaseController{
    protected $modelName = '{{$model}}';
    protected $model = '{{$model}}';
    protected $activeMenu = '{{str_plural($model)}}';
    protected $searchFields = ['title'];

    protected $modelRouteName = '{{$route_name}}';
    @if(isset($middleware) && !empty($middleware))protected $authMiddleware = '{{$middleware}}';
    @endif 
    
    @if(isset($display_field) && !empty($display_field))protected $itemDisplayField = '{{$display_field}}';
    @endif

    @isset($layout)
    @if($layout === 'admin')

    protected $layout = 'elements::layouts.admin';

    @else

    protected $layout = '{{$layout}}';
    
    @endif
    @endisset

    public function __construct(){
        $this->formElements = [
            'title' => [
                'validator' => 'required',
                'type' => 'text',
            ],
        ];

        $this->breadcrumbs = [
            [
                'title' => '{{ucfirst(str_plural($model))}}',
                'url' => route($this->modelRouteName. '.index')
            ]
        ];

        $this->fields = [
            'id',
            'title',
        ];

        parent::__construct();
    }

    public function processTable(&$table){

    }
}