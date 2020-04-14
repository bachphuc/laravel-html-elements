<?php

namespace bachphuc\LaravelHTMLElements\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Session;
use File;
use bachphuc\LaravelHTMLElements\Components\Form;
use bachphuc\LaravelHTMLElements\Components\Table;
use bachphuc\LaravelHTMLElements\Components\BaseElement;
use Response;

class ManageBaseController extends BaseController
{   
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $viewPath = 'manage.base';
    protected $customViews = [];
    protected $modelName = '';
    protected $model;
    protected $modelRouteName = '';
    protected $activeMenu;

    protected $formElements;

    protected $form;
    protected $fields;
    protected $modelClass;
    protected $searchFields;

    protected $editViewPath = '';
    
    protected $bShowPaginator = true;
    protected $title = '';
    protected $createModelUrl = '';
    protected $submitModelUrl = '';
    protected $returnStoreUrl = '';
    protected $returnUpdateUrl = '';
    protected $breadcrumbs = null;

    protected $routeParams = [];
    protected $subjectRouteName = '';
    protected $subject = null;
    protected $queryParams = [];
    protected $bSearchWithId = false;

    protected $theme = 'bootstrap';

    protected $authMiddleware = '';

    protected $rootRoute = '';
    protected $layout = 'layouts.default';

    protected $isShowCreateButton = true;

    protected $itemDisplayField = '';
    protected $pageConfig = [];
    protected $_initialized = false;

    public function __construct(){
        $this->loadConfig($this->pageConfig);
        if(!empty($this->authMiddleware)){
            $this->middleware($this->authMiddleware);
        }
        $this->modelClass = model_class($this->model);
    }

    public function loadConfig($params = []){
        if(empty($params)) return;
        if(isset($params['form'])){
            $this->formElements = $params['form'];
        }

        if(isset($params['table'])){
            $tableConfig = $params['table'];
            if(isset($tableConfig['fields'])){
                $this->fields = $tableConfig['fields'];
            }
        }

        if(isset($params['auth_middleware'])){
            $this->authMiddleware = $params['auth_middleware'];
        }

        if(isset($params['model_route_name'])){
            $this->modelRouteName = $params['model_route_name'];
        }

        if(isset($params['model'])){
            $this->model = $params['model'];
        }

        if(isset($params['active_menu'])){
            $this->activeMenu = $params['active_menu'];
        }

        if(isset($params['search_fields'])){
            $this->searchFields = $params['search_fields'];
        }

        if(isset($params['model_name'])){
            $this->modelName = $params['model_name'];
        }

        if(isset($params['item_display_field'])){
            $this->itemDisplayField = $params['item_display_field'];
        }

        if(isset($params['custom_views'])){
            $this->customViews = $params['custom_views'];
        }

        if(isset($params['breadcrumbs'])){
            $this->breadcrumbs = $params['breadcrumbs'];
        }
    }

    public function initFormInput($isUpdate = false){
        if(!empty($this->formElements)){
            $form = new Form();
            $form->setIsUpdate($isUpdate);
            $form->setModel($this->model);
            $form->hasFile(true);
            $form->setElements($this->formElements);
    
            $form->setTheme($this->theme);
            $this->form = $form;
        }
    }

    public function init(){
        if($this->_initialized) return;
        $this->_initialized = true;
        if(empty($this->subjectRouteName)){
            $this->subjectRouteName = $this->model;
        }
        $request = request();
        $params = $request->route()->parameters();
        foreach($params as $key => $value){
            $class = model_class($key);
            if($class){
                $item = $class::find($value);
                if($item){
                    if(!empty($this->subjectRouteName) && $this->subjectRouteName == $key){
                        $this->subject = $item;
                    }
                    else{
                        $this->routeParams[$key] = $item;
                    }
                }
            }
        }
    }

    public function getId($id){
        return $id;
    }

    public function getItem($id){
        if($this->subject) return $this->subject;
        $item = $this->modelClass::findOrFail($id);
        $this->subject = $item;
        return $item;
    }

    public function processTable(&$table){

    }

    public function processQuery(Request $request, &$query){

    }
    

    public function index(Request $request){
        $this->init();
        $modelClass = model_class($this->model);
        if(empty($modelClass)){
            die('Model is invalid: '. $this->model);
        }
        $this->queryParams = [];

        $query = $modelClass::orderBy('created_at', 'DESC');

        if($request->query('keyword') && !empty($this->searchFields)){
            $keyword = $request->query('keyword');
            $this->queryParams['keyword'] = $request->query('keyword');

            preg_match('/^#(\d+)$/', $keyword, $output_array);
            $bSpecialSearch = false;
            if(!empty($output_array) && count($output_array) == 2){
                $query->where('id', $output_array[1]);
                $this->bSearchWithId = true;
            }
            else if(strpos($keyword, ':') !== false){
                $searchParts = explode(':', $keyword);
                if(count($searchParts) >= 2){
                    $prop = trim($searchParts[0]);
                    if(!empty($prop)){
                        $tmpObj = new $modelClass();
                        if($tmpObj->hasField($prop)){
                            $bSpecialSearch = true;
                            $query->where($prop, trim($searchParts[1]));
                        }
                    }
                }
            }       
            
            if(!$this->bSearchWithId && !$bSpecialSearch){
                $query->where(function($query) use($keyword){
                    if(!empty($this->searchFields)){
                        foreach($this->searchFields as $field){
                            $query->orWhere($field, 'LIKE', '%' .$keyword . '%');
                        }
                    }
                });
            }
        }

        if(!$this->bSearchWithId){
            $this->processQuery($request, $query);
        }

        $limit = $request->query('limit') ? $request->query('limit') : 20;
        $this->queryParams['limit'] = $limit;
        if($this->bShowPaginator){
        $items = $query->orderBy('id', 'desc')->paginate($limit);
        }
        else{
            $items = $query->orderBy('id', 'desc')->get();
        }

        $table = Table::create([
            'items' => $items,
            'modelName' => $this->modelName,
            'model' => $this->model,
            'item_title' => trans('lang.' . $this->model),
            'fields' => $this->fields,
            'show_action_buttons' => true,
            'show_paginate' => $this->bShowPaginator,
            'params' => $this->queryParams,
            'theme' => $this->theme,
            'model_route_name' => $this->modelRouteName,
            'route_params' => $this->routeParams
        ]);

        // set custom action in table
        if(isset($this->pageConfig['table']['custom_actions'])){
            $table->setCustomAction($this->pageConfig['table']['custom_actions']);
        }

        $this->processTable($table);

        $this->createModelUrl = $this->resolveItemUrl(null, 'create', $this->routeParams);

        return view($this->getView('index'), [
            "items" => $items,
            "model" => $this->model,
            "modelName" => $this->modelName,
            "params" => $this->queryParams,
            "table" => $table,
            'breadcrumbs' => $this->breadcrumbs,
            'rootRoute' => $this->rootRoute,
            'createModelUrl' => $this->createModelUrl,
            'isShowCreateButton' => $this->isShowCreateButton,
            'layout' => $this->layout,
            'searchFields' => $this->searchFields
        ]);
    }

    public function getActionUrl($action, $routeParams = []){
        if($action === 'create'){
            if(method_exists($this->modelClass, 'getAdminCreateRoute')){
                return get_route($this->modelClass::getAdminCreateRoute(), $routeParams);
            }
        }
        else if($action === 'index'){
            if(method_exists($this->modelClass, 'getAdminRouteName')){
                return get_route($this->modelClass::getAdminRouteName(), $routeParams);
            }
        }
        return url('');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->init();
        $this->initFormInput();
        $this->form->setAttribute('action', $this->resolveItemUrl(null, 'index', $this->routeParams));
        $this->form->post();

        return view($this->getView('create'), [
            "modelName" => $this->modelName,
            "form" => $this->form,
            'breadcrumbs' => $this->breadcrumbs,
            'layout' => $this->layout,
            'model' => $this->model
        ]);
    }

    public function customStoreValidators(Request $request){
        return true;
    }

    public function beforeStore(Request $request, &$data){

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->init();
        $this->initFormInput();
        $this->validate($request, $this->form->getValidators());

        $check = $this->customStoreValidators($request);
        if(!$check) return;

        $data = $request->all();
        // create new hook before submit
        $this->beforeStore($request, $data);
        
        $this->form->post($data);

        // create new hook before submit
        $this->beforeStore($request, $data);
        
        $item = $this->form->submit();

        // process after create item
        if(!$item){
            $request->flash();
            Session::flash('error', "Create $this->model failed.");
            return redirect()->to($this->resolveItemUrl(null, 'create', $this->routeParams));
        }

        // create new hook after create item
        $this->afterStore($request, $item, $data);

        $itemType = str_replace('_', ' ' , $this->model);
        $message = "Create " . str_clean_title($itemType) . " successfully.";
        if(is_request_json()){
            return [
                'status' => true,
                'message' => $message,
                'item' => $item
            ];
        }

        Session::flash('message', $message);
        if(!empty($this->returnStoreUrl)){
            return redirect()->to($this->returnStoreUrl);
        }
        return redirect()->to($this->resolveItemUrl(null, 'index', $this->routeParams));
    }

    public function afterStore(Request $request, $item, $data){
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ModelBase  $item
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $this->init();
        if(isset($this->customViews['show'])){
            return view($this->getView('show') , [
                "modelName" => $this->modelName,
                'breadcrumbs' => $this->breadcrumbs,
                'self' => $this,
                'layout' => $this->layout,
                'model' => $this->model,
                'subject' => $this->subject
            ]);
        }
        return $this->edit($id);
    }

    public function resolveItemAction($item, $action){
        if($action === 'title'){
            if(method_exists($item, 'getTitle')){
                return $item->getTitle();
            }
            else if(!empty($this->itemDisplayField)){
                return $item->{$this->itemDisplayField};
            }
        }
        else if($action === 'href'){
            if(method_exists($item, 'getHref')){
                return $item->getHref();
            }
        }

        return null;
    }

    public function resolveItemUrl($item, $action, $params = []){
        if(!empty($this->modelRouteName)){
            if(empty($params) && !empty($this->routeParams)){
                $params = $this->routeParams;
            }
            $route = $this->modelRouteName . '.' . $action;
            if(\Route::has($route)){
                if($item){
                    $params['id'] = $item->id;
                }
                return route($route, $params);
            }
        }
        return url('');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ModelBase  $item
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->init();
        $this->initFormInput(true);
        $item = $this->getItem($this->getId($id));
        
        $this->breadcrumbs[] = [
            'title' => $this->resolveItemAction($item, 'title'),
            'url' => $this->resolveItemUrl($item, 'edit')
        ];

        $this->breadcrumbs[] = [
            'title' => 'Edit',
            'active' => true
        ];

        $this->form->setAttribute('action', $this->resolveItemUrl($item, 'update'));
        $this->form->setMethod('PUT');
        $this->form->setItem($item);

        $this->form->post();

        return view($this->getView('edit') , [
            "modelName" => $this->modelName,
            "item" => $item,
            "form" => $this->form,
            'breadcrumbs' => $this->breadcrumbs,
            'self' => $this,
            'layout' => $this->layout,
            'model' => $this->model
        ]);
    }

    public function getView($path){
        if(isset($this->customViews[$path])){
            return $this->customViews[$path];
        }
        return BaseElement::VIEW_BASE_PATH . '::' . $this->theme . '.' . $this->viewPath .'.' . $path;
    }

    public function customUpdateValidators(Request $request, $item){
        return true;
    }

    public function beforeUpdate(Request $request, $item){
        
    }

    public function afterUpdate(Request $request, $item){
        
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ModelBase  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->init();
        $this->initFormInput(true);
        $modelClass = model_class($this->model);
        $item = $this->getItem($this->getId($id));
        
        $this->validate($request, $this->form->getValidators(true));
        $check = $this->customUpdateValidators($request, $item);
        if(!$check) return;
        $this->form->setItem($item);
        $this->form->populate();
        
        $data = $request->all();
        $this->beforeUpdate($request, $item);
        
        $item = $this->form->submit();
        
        $this->afterUpdate($request, $item);
        if(!$item){
            $request->flash();
            Session::flash('error', "Update $this->model failed.");
            return redirect()->to($this->resolveItemUrl(null, 'index', $this->routeParams));
        }

        $itemType = str_replace('_', ' ' , $this->model);
        Session::flash('message', "Update " . str_clean_title($itemType) . " successfully.");
        $this->routeParams['id'] = $item->id;
        return redirect()->to($this->resolveItemUrl(null, 'edit', $this->routeParams));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ModelBase  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $this->init();
        $item = $this->getItem($id);
        if(method_exists($item, 'remove')){
            $item->remove();
        }
        else{
            $item->delete();
        }
        if(is_request_json()){
            return [
                'status' => true, 
                'message' => 'Delete item successful.'
            ];
        }
        Session::flash('message', "Delete $this->model successfully.");
        return redirect()->to($this->resolveItemUrl(null, 'index', $this->routeParams));
    }

}