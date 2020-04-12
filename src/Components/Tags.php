<?php
namespace bachphuc\LaravelHTMLElements\Components;

use bachphuc\LaravelHTMLElements\Models\ModelBase;

class Tags extends BaseElement
{   
    protected $viewPath = 'tags';

    public function setOptionsAttribute($options){
        if(isset($options['model']) && !empty($options['model'])){
            $class = ModelBase::getModelClass($options['model']);
            if(!empty($class)){
                $items = $class::all();
                $this->setAttribute('items', $items);
                $this->setAttribute('dataType', 'model');
            }
        }
        else if(isset($options['data']) && !empty($options['data'])){
            $this->setAttribute('items', $options['data']);
            $this->setAttribute('dataType', 'array');
        }
    }

    public function process($item = null, $data = []){
        if(empty($item)) return;

        $options = $this->getAttribute('options');
        $value = $this->getAttribute('value');
        if($options && isset($options['model']) && $options['model'] == 'tag'){
            // tag is feature default of model base then very simple :)
            // update tag to item
            $item->updateTags($value);
            return;
        }
        $relationship = $this->getAttribute('relationship');
        if(empty($relationship)) return;
        $type = $item->getType();
        $shipModel = ModelBase::getModelClass($relationship['model']);
        if(!$shipModel) return;
        $relationShipModel = ModelBase::getModelClass(studly_case($type . '_' . $relationship['model']));
        if(!$relationShipModel) return;
        // clean all item between this item and relationship model
        $relationShipModel::where($type . '_id', $item->id)->delete();
        // loof and add new item
        foreach($value as $v){
            if(is_numeric($v)){
                $relationShipModel::create([
                    $type . '_id' => $item->id,
                    $relationship['model'] . '_id' => $v
                ]);
            }
            else{
                // it's string need to create new
                $titleField = isset($relationship['title']) ? $relationship['title'] : 'title';
                $p = [
                    $titleField => trim($v)
                ];
                if(isset($relationship['alias']) && $relationship['alias']){
                    $p['alias'] = str_slug($v, '-');
                }
                if(isset($relationship['user']) && $relationship['user']){
                    $p['user_id'] = auth()->user()->id;
                }
                $relationshipItem =  $shipModel::create($p);
                $t = [
                    $type . '_id' => $item->id,
                    $relationship['model'] . '_id' => $relationshipItem->id
                ];
   
                $relationShipModel::create($t);
            }
        }
    }

    public function setItem($item){
        parent::setItem($item);
        if(empty($item)) return;

        $options = $this->getAttribute('options');
        $value = $this->getAttribute('value');
        if($options && isset($options['model']) && $options['model'] == 'tag'){
            // tag is feature default of model base then very simple :)
            // update tag to item
            $tags = $item->getTags();
            $ids = [];
            foreach($tags as $tag){
                $ids[] = $tag->id;
            }
            $this->setAttribute('value', $ids);
            return;
        }

        $relationship = $this->getAttribute('relationship');
        if(empty($relationship)) return;
        $value = $this->getAttribute('value');
        $type = $item->getType();
        $shipModel = ModelBase::getModelClass($relationship['model']);
        if(!$shipModel) return;
        $relationShipModel = ModelBase::getModelClass(studly_case($type . '_' . $relationship['model']));
        if(!$relationShipModel) return;
        // get all ids of this map
        $maps = $relationShipModel::where($type. '_id', $item->id)
        ->get();

        $ids = [];
        foreach($maps as $m){
            $ids[] = $m[$relationship['model']. '_id'];
        }

        $this->setAttribute('value', $ids);
    }
}
