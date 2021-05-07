<?php

namespace bachphuc\LaravelHTMLElements\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Session;
use File;
use Response;

class TinymceController extends BaseController
{   
    public function uploadImage(Request $request){
        $resultFiles = [];
        $image = '';
        if(!function_exists('photo_storage')){
            return [
                'status' => false,
                'message' => 'Missing php-laravel-helpers library'
            ];
        }
        if ($request->hasFile('files')) {
            foreach ($request->files as $files) {
                foreach ($files as $file) {
                    $path = photo_storage($file);
                    list($width, $height) = getimagesize($path);
                    if ($width > 1024 || $height > 1024) {
                        photo_resize($path);
                    }

                    $resultFiles[] = [
                        'url' => '/' . $path,
                    ];
                }
            }
        }
        if(count($resultFiles)){
            $image = $resultFiles[0]['url'];
        }
        return [
            'status' => true,
            'files' => $resultFiles,
            'image' => $image
        ];
    }
}