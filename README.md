# HTML ELEMENT FOR LARAVEL

## Install

Install with composer: `composer install bachphuc/laravel-html-elements`

## Components

### TextEditor

1. class bachphuc\LaravelHTMLElements\Components\TextEditor;

2. Options:
- allow_upload_image: boolean.
- upload_image_url: required if allow_upload_image is true.
- require add `HtmlElement::tinymceRoutes();` to route. Optional paramters `path` and `name` define.
- default tinymce upload image router name: `tinymce.image.upload`

### Table
1. class bachphuc\LaravelHTMLElements\Components\TextEditor;

2. Options: 
- disableEditModalMode: Optional, default: false.