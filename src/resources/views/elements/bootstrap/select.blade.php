<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            @php
                $value = isset($value) ? $value : null;
                $validators = isset($validators) ? $validators : [];
                $inputTitle = title_case(isset($title) ? $title : (isset($name) ? $name : ''));
            @endphp
            <label>{{str_clean_title($inputTitle)}}</label>
            <select class="form-control" name="{{isset($name) ? $name : ''}}" {{in_array('required', $validators) ? 'required' : ''}}>
                <option value="{{isset($default_value) ? $default_value : ''}}">@lang('lang.select') {{str_clean_title($inputTitle)}}</option>
                @if(isset($dataType) && isset($items) && !empty($items))
                    @if($dataType == 'model')
                        @foreach($items as $item)
                        @php
                            $itemTitle = $item->getTitle();
                            if(isset($options) && isset($options['getTitle'])){
                                $itemTitle = $options['getTitle']($item);
                            }
                        @endphp
                        <option {{$value == $item->getId() ? 'selected' : ''}} value="{{$item->getId()}}">{{$itemTitle}}</option>
                        @endforeach
                    @elseif($dataType == 'array')
                        @foreach($items as $key => $item)
                        @php
                            $v = is_array($item) ? $item['value'] : $item;
                            $t = is_array($item) ? $item['title'] : $item;
                        @endphp
                        <option {{$value == $v ? 'selected' : ''}} value="{{$v}}">{{ucfirst(str_clean_title($t))}}</option>
                        @endforeach
                    @endif
                @endif
            </select>
        </div>
    </div>
</div>