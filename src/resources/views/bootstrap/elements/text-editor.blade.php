<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            @php
            $validators = isset($validators) ? $validators : [];
            $name = isset($name) ? $name : '';
            @endphp
            <label class="control-label">{{isset($title) ? $title : ''}}</label>
            <div class="mt20">
                <textarea id="{{isset($id) ? $id : $name}}" class="tinymce-editor" name="{{isset($name) ? $name : ''}}" {{in_array('required', $validators) ? '' : ''}} placeholder="{{isset($placeholder) ? $placeholder : ''}}">{{isset($value) ? $value : ''}}</textarea>
            </div>
        </div>
    </div>
</div>