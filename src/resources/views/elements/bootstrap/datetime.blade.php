<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            @php
            $validators = isset($validators) ? $validators : [];
            $title = isset($title) ? $title : (isset($name) ? $name : '');
            @endphp
            <label class="control-label">{{str_clean_title(ucfirst($title))}}</label>

            <div class='input-group date datetimepicker'>
                <input type="text" name="{{isset($name) ? $name : ''}}" value="{{isset($value) ? $value : ''}}" placeholder="{{isset($placeholder) ? $placeholder : ''}}" class="form-control" {{in_array('required', $validators) ? 'required' : ''}}>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
    </div>
</div>