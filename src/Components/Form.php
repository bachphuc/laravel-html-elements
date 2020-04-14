<?php

namespace bachphuc\LaravelHTMLElements\Components;

class Form extends BaseElement
{
    /**
     * $renderActionButtons
     */

    protected $data = [];
    protected $elementsData = [];
    protected $elements = [];
    protected $viewPath = 'form';
    protected $model = null;
    protected $specialFields = ['user', 'alias'];
    protected $defaultThumbnailSizes = [120, 300, 500];
    protected $item = null;

    public function setItem($item){
        $this->item = $item;
        $data = $item->toArray();
        $this->populate($data);
        foreach($this->elements as $ele){
            $ele->setItem($item);
        }
    }

    public function hasFile($b){
        $this->setAttribute('has_file', $b);
    }

    /**
     * custom render action button
     */
    public function setRenderActionButtons($render){
        $this->setAttribute('renderActionButtons', $render);
    }

    public function setElements($data){
        // set element data
        $this->elementsData = $data;

        $this->elements = [];
        // init elements
        
        foreach($this->elementsData as $k => $tmp){
            if(!is_numeric($k) && in_array($k, $this->specialFields)){
                continue;
            }
            $key = is_array($tmp) ? $k : $tmp;
            $ele = is_array($tmp) ? $tmp : [
                'type' => 'text'
            ];

            // set default input type text
            if(!isset($ele['type'])){
                $ele['type'] = 'text';
            }
            // special field
            if(!in_array($key, $this->specialFields)){
                $class = $this->getElementClass($ele['type'], isset($ele['module']) ? $ele['module'] : '');
                if(empty($class)){
                    if(!isset($ele['module'])){
                        die('Missing implement App\\Http\\Components\\' . studly_case($ele['type']));
                    }
                    else{
                        die('Missing implement Modules\\' . ucfirst($ele['module']) . '\\App\\Http\\Components\\' . studly_case($ele['type']));
                    }
                }
                $element = new $class();
                $element->setName($key);
                $ele['name'] = $key;
                $element->setIsUpdate($this->isUpdate);
                $element->setAttributes($ele);                
                $element->setTheme($this->getTheme());
                $element->setModule($this->getModule());
                $this->elements[$key] = $element;
            }
        }
    }

    public function setTheme($t){
        parent::setTheme($t);
        foreach($this->elements as &$ele){
            $ele->setTheme($this->getTheme());
        }
    }

    public function setModule($m){
        parent::setModule($m);

        foreach($this->elements as &$ele){
            $ele->setModule($this->getModule());
        }
    }

    public function render($params = []){
        $params['data'] = $this->data;
        $params['elements'] = $this->elements;
        return parent::render($params);
    }

    public function populate($data = []){
        if(empty($data)){
            $request = request();
            $data = $request->all();
        }
        $this->data = $data;
        foreach($this->elements as &$ele){
            $name = $ele->getAttribute('name');
            if(isset($data[$name])){
                $ele->setValue($data[$name]);
            }
        }
    }

    public function addData($key, $value){
        $this->data[$key] = $value;
        if(!isset($this->elements[$key])) return;
        $ele = $this->elements[$key];
        $ele->setValue($value);
    }

    public function post($data = []){
        $request = request();
        if($request->isMethod('POST')){
            if(empty($data)){
                $data = $request->all();
            }
            
            $this->populate($data);
        }
        else{
            $data = $request->old();
            if(!empty($data)){
                $this->populate($data);
            }
        }
    }

    public function getValidators($isUpdate = false){
        $results = [];
        foreach($this->elementsData as $key => $ele){
            if(isset($ele['type']) && $ele['type'] == 'video_input'){
                $sValidator = 'file|mimetypes:video/mp4,video/mpeg,video/x-matroska,image/gif,video/quicktime,application/octet-stream';
                if(isset($ele['validator'])){
                    $sValidator.= '|' . $ele['validator'];
                }
                $results[$key] = $sValidator;
            }
            else{
                if(isset($ele['validator'])){
                    $results[$key] = $isUpdate && isset($ele['validator_update']) ? $ele['validator_update'] : $ele['validator'];
                }
            }
        }

        return $results;
    }

    public function setModel($m){
        $this->model = $m;
    }

    public function getData(){
        $result = [];
        foreach($this->elements as $ele){
            $name = $ele->getAttribute('name');
            $value = $ele->getValue();
            if($ele->getAttribute('leave_if_empty') && empty($value)){
                // do not pass empty value into this field, should ignore it when update
            }
            else{
                $result[$name] = $value;
            }
        }
        return $result;
    }

    public function getOriginalData(){
        return $this->data;
    }

    public function setMethod($method){
        $this->setAttribute('method', $method);
    }

    public function beforeCreateItem(&$data){

    }

    public function submit(){
        $data = $this->getData();
        $request = request();
        if(!empty($data)){
            $modelClass = model_class($this->model);
            if(!$this->item){
                // create new item
                if(isset($this->elementsData['user'])){
                    $data['user_id'] = auth()->user()->id;
                }
                
                foreach($this->elements as $ele){
                    $ele->prepareProcess($item, $data);
                }
                $this->beforeCreateItem($data);
                $item = $modelClass::create($data);

                if(isset($this->elementsData['alias'])){
                    $aliasField = $this->elementsData['alias'];
                    // $data['alias'] = str_slug($data[$aliasField], '-');
                    if($item->hasField('alias')){
                        $item->alias = str_slug($data[$aliasField], '-') . '-'. $item->id;
                        $item->save();
                    }
                }
                
                foreach($this->elements as $ele){
                    $ele->process($item, $data);
                }

                // TODO: handle upload image
                $hasFile = $this->getAttribute('has_file');
                
                if($hasFile){
                    $bHasFileUpload = false;
                    foreach($this->elements as $ele){
                        // handle upload file
                        if($ele instanceof ImageInput){
                            $imageName = $ele->getAttribute('name');
                            
                            if ($request->hasFile($imageName)) {
                                $bCreateThumbnail = $ele->getAttribute('thumbnail');
                                $bSkipSize = $ele->getAttribute('skip_size', true);

                                $item->uploadPhoto([
                                    'name' => $imageName,
                                    'field' => $imageName,
                                    'skip_size' => $bSkipSize
                                ], $bCreateThumbnail);
                            }
                        }
                    }
                }

                $this->item = $item;
                return $item;
            }
            else{
                if(isset($data['id'])){
                    unset($data['id']);
                }
                if(isset($data['created_at'])){
                    unset($data['created_at']);
                }
                if(isset($data['updated_at'])){
                    unset($data['updated_at']);
                }
                
                foreach($this->elements as $ele){
                    $ele->prepareProcess($item, $data);
                }
                
                // update new item
                $this->item->update($data);

                $hasFile = $this->getAttribute('has_file');

                // handle upload file
                if($hasFile){
                    $bHasFileUpload = false;
                    foreach($this->elements as $ele){
                        
                        if($ele instanceof ImageInput){
                            $imageName = $ele->getAttribute('name');
                            
                            if ($request->hasFile($imageName)) {

                                $bCreateThumbnail = $ele->getAttribute('thumbnail');
                                $bSkipSize = $ele->getAttribute('skip_size', true);

                                $this->item->uploadPhoto([
                                    'name' => $imageName,
                                    'field' => $imageName,
                                    'skip_size' => $bSkipSize
                                ], $bCreateThumbnail);
                            }
                        }
                    }
                }

                foreach($this->elements as $ele){
                    $ele->process($this->item, $data);
                }
                return $this->item;
            }
        }
        else{
            return null;
        }
    }

    public static function create($params = []){
        $form = new Form();
        if(isset($params['model'])){
            $form->setModel($params['model']);
        }
        
        $form->hasFile(isset($params['has_file']) ? $params['has_file'] : false);
        
        if(isset($params['elements'])){
            $form->setElements($params['elements']);
        }
        
        $form->setTheme(isset($params['theme']) ? $params['theme'] : 'bootstrap');

        if(isset($params['action'])){
            $form->setAttribute('action', $params['action']);
        }

        if(isset($params['form_title'])){
            $form->setAttribute('form_title', $params['form_title']);
        }

        if(isset($params['data'])){
            $form->populate($params['data']);
        }

        return $form;
    }
}
