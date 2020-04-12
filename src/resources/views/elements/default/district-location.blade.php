@php
    $validators = isset($validators) ? $validators : [];
    $elementId = isset($id) ? $id : str_random(8);
    $provinces = \App\Models\Province::getAll();
    $districts = \App\Models\District::getAll();
    $districtJsName = 'obj' . str_random(8);
    $provinceJsOnChange = 'fun'. str_random(8);
    $districtId = isset($value) ? $value : 0;
    $district = null;
    if(!empty($districtId)){
        $district = \App\Models\District::find($districtId);
    }
@endphp

<div>
    <h4 class="control-label">@if(in_array('required', $validators))<span tred>*</span> @endif @lang('lang.province')</h4>
    <div>
        <select class="pinput" name="province_id" {{in_array('required', $validators) ? 'required' : ''}} onchange="{{$provinceJsOnChange}}(this)">
            <option value="">@lang('lang.select_province')</option>
            @foreach($provinces as $province)
                <option value="{{$province->getId()}}" {!! $district && $district->province_id == $province->getId() ? 'selected' : '' !!}>{{$province->getTitle()}}</option>
            @endforeach
        </select>
    </div>
</div>

<div>
    <h4 class="control-label">@if(in_array('required', $validators))<span tred>*</span> @endif {{isset($title) ? $title : trans('lang.district')}}</h4>
    <div>
        <select id="select_district" class="pinput" name="{{isset($name) ? $name : 'district_id'}}" {{in_array('required', $validators) ? 'required' : ''}}>
            <option value="">@lang('lang.select_district')</option>
            @if($district)
            @foreach($districts as $d)
            <option value="{{$d->id}}" {!! $district->id == $d->id ? 'selected' : '' !!}>{{$d->getTitle()}}</option>
            @endforeach
            @endif
        </select>
    </div>
</div>

<script>
    var {{$districtJsName}} = {!! json_encode($districts) !!};

    function {{$provinceJsOnChange}}(ele){
        var id = parseInt(ele.value);
        console.log(id);
        var selectDistrict = document.getElementById('select_district');
        while (selectDistrict.firstChild) {
            selectDistrict.removeChild(selectDistrict.firstChild);
        }
        var options = document.createDocumentFragment();
        var label = document.createElement('option');
        label.value = "";
        label.text = '{{trans('lang.select_district')}}';
        options.appendChild(label);
        {{$districtJsName}}.forEach(e => {
            if(e.province_id === id) {
                var ele = document.createElement('option');
                ele.value = e.id;
                ele.text = e.title;
                options.appendChild(ele);
            }
        });

        selectDistrict.appendChild(options);
    }
</script>