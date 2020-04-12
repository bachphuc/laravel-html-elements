<?php
namespace bachphuc\LaravelHTMLElements\Components;

use bachphuc\LaravelHTMLElements\Models\ModelBase;

 /**
  * type: video_input
  * title : 
  * extension : append when save
  * disk : save to disk
  * path: save to path of disk
  * validator : inherit from base
  * jsValidators: only validate by javascript, pass by server
  */
class VideoInput extends BaseElement
{
    protected $viewPath = 'video-input';

    public function prepareProcess(&$item = null, &$data = []){
        $name = $this->getAttribute('name');
        $extensionField = $this->getAttribute('extension');
        if(request()->hasFile($name)){
            // process upload video
            $disk = $this->getAttribute('disk', 'public');
            $path = $this->getAttribute('path', 'upload/videos');
            $request = request();
            $path = $request->{$name}->store($path, $disk);
            $data[$name] = $path;
            $data['disk'] = $disk;
            $data['size'] = $request->{$name}->getClientSize();
            if($extensionField){
                $data['extension'] = $request->{$name}->extension();
            }
        }
    }
}
