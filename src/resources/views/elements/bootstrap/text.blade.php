<div class="row">
    <div class="col-md-12">
        <div class="form-group label-floating">
            @php
            $validators = isset($validators) ? $validators : [];
            $value = isset($value) ? $value : (isset($default) ? $default : '');
            $inputType = isset($input_type) ? $input_type : 'text';
            $name = isset($name) ? $name : '';
            $autocomplete = '';
            if($inputType == 'email'){
                $autocomplete = "autocomplete='email'";
            }
            if($name == 'first_name'){
                $autocomplete = "autocomplete='give-name'";
            }
            else if($name == 'last_name'){
                $autocomplete = "autocomplete='family-name'";
            }
            
            @endphp
            <label class="control-label">{{isset($title) ? str_clean_title($title) : ''}} @if(in_array('required', $validators))<span class="text-danger">*</span> @endif </label>
            <input {!! $autocomplete !!} type="{{$inputType}}" name="{{$name}}" value="{{$value}}" placeholder="{{isset($placeholder) ? $placeholder : ''}}" class="form-control" {{in_array('required', $validators) ? 'required' : ''}}>
        </div>
    </div>
</div>