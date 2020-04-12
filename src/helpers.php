<?php
    function model_class($str){
        if(empty($str)) return null;
        
        if($str instanceof \Illuminate\Database\Eloquent\Model) return $str;
        
        if(class_exists($str)) return $str;

        $strClass = "\\App\\Models\\" . ucfirst($str);
        if(class_exists($strClass)) return $strClass;
        return null;
    }
