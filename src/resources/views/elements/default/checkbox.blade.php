<div>
    @php
        $selected = isset($selected) ? $selected : 1;
        $value = isset($value) ? $value : null;
    @endphp
    <h4 class="control-label">{{title_case(isset($title) ? $title : (isset($name) ? $name : ''))}}</h4>
    <div>
        <label checkbox="big">{{isset($sub_title) ? $sub_title : ''}}
            <input value="{{$selected}}" name="{{isset($name) ? $name : ''}}" type="checkbox" class="form-control" {{$value == $selected ? 'checked' : ''}} />
        </label>
    </div>
</div>

