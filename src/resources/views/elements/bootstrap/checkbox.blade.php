<div class="row">
    <div class="col-md-12">
        @php
            $selected = isset($selected) ? $selected : 1;
            $value = isset($value) ? $value : null;
        @endphp

        <label>{{title_case(isset($title) ? $title : (isset($name) ? $name : ''))}}</label>

        <div class="checkbox">
            <label>
                <input type="checkbox" name="{{isset($name) ? $name : ''}}" value="{{$selected}}" {{$value == $selected ? 'checked' : ''}} />
            </label>
        </div>
    </div>
</div>