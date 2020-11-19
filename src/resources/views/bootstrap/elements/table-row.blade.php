@php
    $rowAttribute = '';
    if(isset($rowAttributeRender)){
        $rowAttribute = $rowAttributeRender($item);
    }
@endphp
<tr {!! $rowAttribute !!} >
    @foreach($fields as $k => $field)
    @php
        $type = !is_array($field) ? 'text' : (isset($field['type']) ? $field['type'] : 'text' );
        $v = $item->{str_key_from_array($field, $k)};
        if(isset($field['uppercase']) && $field['uppercase']){
            $v = strtoupper($v); 
        }
        $renderItem = isset($field['render']) ? $field['render'] : null;
    @endphp
    <td class="{{$k != 'id' ? 'wrap-text' : ''}}">
        {{--  render default  --}}
        @if(!$renderItem)
            @if($type == 'image')
                @if($v)
                    <img src="{{url($v)}}" class="w50" onerror="onImageError(this)" />
                @else
                    <img src="{{$item->getImage()}}" onerror="onImageError(this)" class="w50" />
                @endif
            @elseif($type == 'link')
            <a href="{{$item->getHref()}}">{{$v}}</a>
            @elseif($type == 'admin_link')
            <a href="{{$item->getAdminHref()}}">{{$v}}</a>
            @elseif($type == 'date_time')
            {{format_datetime($v)}}
            @elseif($type == 'int')
            {{(int) $v}}
            @elseif($type == 'object')
                @if(!empty($v))
                <a href="{{$v->getHref()}}">{{$v->getTitle()}}</a>
                @endif
            @else
                @isset($field['label'])
                <label class="label label-block label-{{is_string($field['label']) ? $field['label'] : $field['label'][$v]}}">
                @endisset
                {{isset($field['limit']) ? str_limit($v, $field['limit']) : str_replace('_', ' ', ucfirst($v))}}
                @isset($field['label'])
                </label>
                @endisset
            @endif
        @else
        {{--  render with custom render  --}}
        {!! $renderItem($item) !!}
        @endif
    </td>
    @endforeach
    @if($isShowActionButtons)
    <td>
        <div class="td-actions text-right">
            @if($isShowDeleteButton)
            <a rel="tooltip" title="Delete Item" class="btn btn-danger btn-simple btn-xs" data-toggle="modal" data-target="#deleteConfirmModal" onclick="onDeleteItem({{$item->id}})">
                <i class="material-icons">close</i>
            </a>

            <form id="form-{{$item->id}}" action="{{ $self->handleUrl($item, 'destroy') }}" method="POST" style="display: none;">
                {{ csrf_field() }} {{ method_field('delete') }}
            </form>
            @endif

            @if($isShowEditButton)
            <a rel="tooltip" title="Edit Item" class="btn btn-primary btn-simple btn-xs fast-link" href="{{$self->handleUrl($item, 'edit')}}">
                <i class="material-icons">edit</i>
            </a>
            @endif

            @if(isset($customActions) &&!empty($customActions))
            @foreach($customActions as $action)
                @php
                    $bCanShowAction = isset($action['validate']) ? $action['validate']($item) : true;
                    $actionType = isset($action['type']) ? $action['type'] : 'success';
                @endphp
                @if($bCanShowAction)
                    @if(isset($action['render']))
                    {!! $action['render']($item) !!}
                    @else
                        @if(isset($action['route']))
                        <a  rel="tooltip" title="{{isset($action['tooltip']) ? $action['tooltip'] : ''}}" class="btn btn-simple btn-xs btn-{{$actionType}}" href="{{route($action['route'] , ['id' => $item->id])}}">{!! $action['title'] !!}</a>
                        @elseif(isset($action['url']))
                        <a  rel="tooltip" title="{{isset($action['tooltip']) ? $action['tooltip'] : ''}}" class="btn btn-simple btn-xs btn-{{$actionType}}" href="{{$action['url']}}">{!! $action['title'] !!}</a>
                        @endif
                    @endif
                @endif
            @endforeach
            @endif
        </div>
    </td>
    @endif
</tr>