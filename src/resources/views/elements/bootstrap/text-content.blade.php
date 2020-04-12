<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            @php
            $validators = isset($validators) ? $validators : [];
            @endphp
            <label class="control-label">{{isset($title) ? $title : ''}}</label>
            <div class="mt20">
                <textarea class="form-control" name="{{isset($name) ? $name : ''}}" {{in_array('required', $validators) ? 'required' : ''}} placeholder="{{isset($placeholder) ? $placeholder : ''}}">{{isset($value) ? $value : ''}}</textarea>
            </div>
        </div>
    </div>
</div>