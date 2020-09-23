<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            @php
                $checked = isset($value) ? $value : [];
            @endphp
            <label>{{title_case(isset($title) ? $title : (isset($name) ? $name : ''))}}</label>
            <div class="categories-control {{isset($class) ? $class : ''}}">
                @if(isset($dataType) && isset($items) && !empty($items))
                @if($dataType == 'model')
                    @foreach($items as $item)
                    <div>
                        <div class="checkbox">
                            <label>
                                <input name="{{isset($name) ? $name : ''}}[]" value="{{$item->getId()}}" id="{{isset($name) ? $name : ''}}-control-item-{{$item->getId()}}" type="checkbox" {{in_array($item->getId(), $checked) ? 'checked' : ''}} />
                            </label>
                        </div>
                        <label for="{{isset($name) ? $name : ''}}-control-item-{{$item->getId()}}">{{$item->getTitle()}}</label>
                    </div>
                    @endforeach
                @elseif($dataType == 'array')
                    @foreach($items as $item)
                    <div>
                        @php
                            $v = is_array($item) ? $item['value'] : $item;
                            $t = is_array($item) ? $item['title'] : $item;
                        @endphp
                        <div class="checkbox">
                            <label>
                                <input name="{{isset($name) ? $name : ''}}[]" value="{{$v}}" id="{{isset($name) ? $name : ''}}-control-item-{{$v}}" type="checkbox" {{in_array($v, $checked) ? 'checked' : ''}} />
                            </label>
                        </div>
                        @if(isset($item['color']))<label for="{{isset($name) ? $name : ''}}-control-item-{{$v}}" class="color" style="background-color: {{$item['color']}};"></label>@endif
                        <label for="{{isset($name) ? $name : ''}}-control-item-{{$v}}">{{$t}}</label>
                    </div>
                    @endforeach
                @endif
                @endif
            </div>
        </div>
    </div>
</div>