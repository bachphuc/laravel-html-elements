<div>
    @php
        $value = isset($value) ? $value : null;
        $validators = isset($validators) ? $validators : [];
    @endphp
    <h4 class="control-label">@if(in_array('required', $validators))<span tred>*</span>@endif {{title_case(isset($title) ? $title : (isset($name) ? $name : ''))}}</h4>
    <div>
        <select class="pinput" name="{{isset($name) ? $name : ''}}" {{in_array('required', $validators) ? 'required' : ''}}>
            @if(isset($dataType) && isset($items) && !empty($items))
                @if($dataType == 'model')
                    @foreach($items as $item)
                    <option {{$value == $item->getId() ? 'selected' : ''}} value="{{$item->getId()}}">{{$item->getTitle()}}</option>
                    @endforeach
                @elseif($dataType == 'array')
                    @foreach($items as $key => $item)
                    @php
                        $v = is_array($item) ? $item['value'] : $key;
                        $t = is_array($item) ? $item['title'] : $item;
                    @endphp
                    <option {{$value == $v ? 'selected' : ''}} value="{{$v}}">{{$t}}</option>
                    @endforeach
                @endif
            @endif
        </select>
    </div>
</div>