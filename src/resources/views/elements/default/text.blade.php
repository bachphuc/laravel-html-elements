<div>
    @php
    $validators = isset($validators) ? $validators : [];
    @endphp
    <h4 class="control-label">@if(in_array('required', $validators))<span tred>*</span> @endif {{isset($title) ? $title : ''}}</h4>
    <div>
        <input type="text" name="{{isset($name) ? $name : ''}}" value="{{isset($value) ? $value : ''}}" placeholder="{{isset($placeholder) ? $placeholder : ''}}" class="pinput" {{in_array('required', $validators) ? 'required' : ''}}>
    </div>
</div>