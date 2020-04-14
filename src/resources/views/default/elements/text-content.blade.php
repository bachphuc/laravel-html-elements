<div>
    @php
    $validators = isset($validators) ? $validators : [];
    @endphp
    <h4 class="control-label">{{isset($title) ? $title : ''}}</h4>
    <div>
        <textarea class="pinput" name="{{isset($name) ? $name : ''}}" {{in_array('required', $validators) ? 'required' : ''}} placeholder="{{isset($placeholder) ? $placeholder : ''}}">{{isset($value) ? $value : ''}}</textarea>
    </div>
</div>