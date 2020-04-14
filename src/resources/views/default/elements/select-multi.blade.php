<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            @php
                $checked = isset($value) ? $value : [];
            @endphp
            <label>{{title_case(isset($title) ? $title : (isset($name) ? $name : ''))}}</label>
            <div class="categories-control">
                @if(isset($dataType) && isset($items) && !empty($items))
                @if($dataType == 'model')
                    @foreach($items as $item)
                    <div>
                        <input name="{{isset($name) ? $name : ''}}[]" value="{{$item->getId()}}" id="{{isset($name) ? $name : ''}}-control-item-{{$item->getId()}}" type="checkbox" {{in_array($item->getId(), $checked) ? 'checked' : ''}} />
                        <label for="{{isset($name) ? $name : ''}}-control-item-{{$item->getId()}}">{{$item->getTitle()}}</label>
                    </div>
                    @endforeach
                @elseif($dataType == 'array')
                    @foreach($items as $item)
                    <div>
                        <input name="{{isset($name) ? $name : ''}}[]" value="{{$item['value']}}" id="{{isset($name) ? $name : ''}}-control-item-{{$item['value']}}" type="checkbox" {{in_array($item['value'], $checked) ? 'checked' : ''}} />
                        <label for="{{isset($name) ? $name : ''}}-control-item-{{$item['value']}}">{{$item['title']}}</label>
                    </div>
                    @endforeach
                @endif
                @endif
            </div>
        </div>
    </div>
</div>