@php
    $isShowActionButtons = isset($isShowActionButtons) ? $isShowActionButtons : false;
    $isShowDeleteButton = isset($isShowDeleteButton) ? $isShowDeleteButton : true;
    $isShowEditButton = isset($isShowEditButton) ? $isShowEditButton : true;
    $isShowPaginator = isset($isShowPaginator) ? $isShowPaginator : true;

    $totalItem = is_array($items) ? count($items) : $items->count();
@endphp

@push('modals')
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-primary" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang('elements::lang.delete_confirm')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{isset($deleteMessage) ? $deleteMessage : trans('elements::lang.delete_confirm_message')}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('elements::lang.close')</button>
                <button type="button" class="btn btn-danger" onclick="deleteItem()">@lang('elements::lang.delete')</button>
            </div>
            <div class="modal-loading"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i> <span>@lang('lang.processing')...</span></div>
        </div>
    </div>
</div>
@endpush

<div class="card-content table-responsive">
    @isset($filters)
    <form method="GET" action="">
        <div class="row">
            @foreach($filters['fields'] as $field)
            <div class="col col-md-2">
                @if($field['type'] == 'select')
                <select class="form-control" name="{{$field['name']}}">
                    <option value="">{{$field['title']}}</option>
                    @foreach($field['options'] as $key => $value)
                    @if($value instanceof BaseElement )
                    <option value="{{$value->getId()}}" {!! request()->query($field['name']) == $value->getId() ? 'selected' : '' !!}>{{$value->getTitle()}}</option>
                    @else
                    <option value="{{$value}}" {!! request()->query($field['name']) == $value ? 'selected' : '' !!}>{{is_numeric ($key) ? $value : $key}}</option>
                    @endif
                    @endforeach
                </select>
                @else
                <input type="text" name="{{$field['name']}}" placeholder="{{$field['title']}}" />
                @endif
            </div>
            @endforeach
            <div class="col col-md-2">
                <button type="submit" class="btn btn-primary">@lang('elements::lang.submit')</button>
            </div>
        </div>
    </form>
    @endisset
    @if($totalItem)
    <table class="table table-hover">
        <thead>
            <tr>
                @foreach($fields as $k => $field)
                <th>{{str_title_from_array($field, $k)}}</th>
                @endforeach
                @if($isShowActionButtons)
                <th>Action</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
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
                        @elseif($type == 'date_time')
                        {{format_datetime($v)}}
                        @elseif($type == 'object')
                        @if(!empty($v))
                        <a href="{{$v->getHref()}}">{{$v->getTitle()}}</a>
                        @endif
                        @else
                            @isset($field['label'])
                            <label class="label label-{{is_string($field['label']) ? $field['label'] : $field['label'][$v]}}">
                            @endisset
                            {{isset($field['limit']) ? str_limit($v, $field['limit']) : $v}}
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
                        <a rel="tooltip" title="Delete Item" class="btn btn-danger btn-simple btn-xs" data-toggle="modal" data-target="#deleteConfirmModal" onclick="window.currentItem = {{$item->id}};">
                            <i class="material-icons">close</i>
                        </a>
    
                        <form id="form-{{$item->id}}" action="{{ $item->getAdminDestroyHref() }}" method="POST" style="display: none;">
                            {{ csrf_field() }} {{ method_field('delete') }}
                        </form>
                        @endif
    
                        @if($isShowEditButton)
                        <a  rel="tooltip" title="Edit Item" class="btn btn-primary btn-simple btn-xs" href="{{$item->getAdminEditHref()}}">
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
                                <a  rel="tooltip" title="{{isset($action['tooltip']) ? $action['tooltip'] : ''}}" class="btn btn-simple btn-xs btn-{{$actionType}}" href="{{route($action['route'] , ['id' => $item->id])}}">{!! $action['title'] !!}</a>
                                @endif
                            @endif
                        @endforeach
                        @endif
                    </div>
                </td>
                @endif
            </tr>
            @endforeach
            
        </tbody>
    </table>
    @else
    <div style="margin-top: 20px;">
        <div class="alert alert-warning">There's no item found.</div>
    </div>
    @endif

    @if($isShowPaginator)
    @include('components.paginate')
    @endif
     
    <script>
        function onImageError(ele){
            ele.src = '{{asset('images/default_user.png')}}';
        }

        function deleteItem(){
            if(!window.currentItem) return;
            let form = document.getElementById('form-' + window.currentItem);
            if(!form) return;
            let r = new XMLHttpRequest(), fd = new FormData(form);
            $('#deleteConfirmModal .modal-loading').addClass('active');

            r.onload = () => {
                if(r.readyState === 4 && r.status === 200){
                    $('#deleteConfirmModal').modal('hide');
                    $('#deleteConfirmModal .modal-loading').removeClass('active');
                    let row = closestParent(form, 'tr', 'table');
                    success('Delete item successful.');
                    if(row){
                        row.parentNode.removeChild(row);
                    }
                }
            };
            r.onerror = (er) => error('Something was wrong. Please try again later.');
            r.open('POST', form.action);
            r.setRequestHeader('Accept', 'application/json');
            r.send(fd);
        }
    </script>
</div>