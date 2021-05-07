<?php
    function element_trans($key, $default = ''){
        return HtmlElement::trans($key, $default);
    }    

    function element_viewpath($path, $theme){
        return HtmlElement::viewPath($path, $theme);
    }
