<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            @php
            $validators = isset($validators) ? $validators : [];
            @endphp
            <h4 class="control-label">@if(in_array('required', $validators))<span tred>*</span> @endif {{isset($title) ? $title : ''}}</h4>
            <div class="mt20">
                <textarea {!! isset($id) ? 'id="' . $id . '"' : '' !!} class="tinymce-editor" name="{{isset($name) ? $name : ''}}" {{in_array('required', $validators) ? 'required' : ''}} placeholder="{{isset($placeholder) ? $placeholder : ''}}">{{isset($value) ? $value : ''}}</textarea>
            </div>
        </div>
    </div>
</div>