@php
    $isShowActionButtons = isset($isShowActionButtons) ? $isShowActionButtons : false;
    $isShowDeleteButton = isset($isShowDeleteButton) ? $isShowDeleteButton : true;
    $isShowEditButton = isset($isShowEditButton) ? $isShowEditButton : true;
    $isShowPaginator = isset($isShowPaginator) ? $isShowPaginator : true;

    $totalItem = is_array($items) ? count($items) : $items->count();
    $tableId = isset($id) ? $id : 'tb' . str_random(8);

    $bRenderOnlyContent = isset($render_only_content) ? $render_only_content : false;
@endphp

@if(!$bRenderOnlyContent)
    {{-- render full table --}}
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
                <div class="modal-loading"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i> <span>@lang('elements::lang.processing')...</span></div>
            </div>
        </div>
    </div>
    @endpush
@endif

@if(!$bRenderOnlyContent)
<div class="card-content">
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
    
    <div class="table-responsive table-data" id="{{$tableId}}">
@endif
        <table class="table table-hover">
            <thead>
                <tr>
                    @foreach($fields as $k => $field)
                    <th>{{str_title_from_array($field, $k)}}</th>
                    @endforeach
                    @if($isShowActionButtons)
                    <th>@lang('elements::lang.action')</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                @include('elements::bootstrap.elements.table-row')
                @endforeach
            </tbody>
        </table>

        @if(!$totalItem)
        <div style="margin-top: 20px;">
            <div class="alert alert-warning">@lang('elements::lang.no_items_found')</div>
        </div>
        @endif

        @if($isShowPaginator)
        @include($self->getPaginateView())
        @endif

@if(!$bRenderOnlyContent)
    </div>
</div>

<script>
    function onDeleteItem(id){
        window.currentItem = id;
        let bHasModalPlugin = false;
        if(window.bootstrap && window.bootstrap.Modal){
            bHasModalPlugin = true;
        }
        else if(window.$ && typeof ($().modal) === 'function'){
            bHasModalPlugin = true;
        }
        
        if(bHasModalPlugin){
            const modal = document.getElementById('deleteConfirmModal');
            if(!modal){
                console.warn("WARNING: ADD: @\stack('modals') to layout");
                bHasModalPlugin = false;
            }
        }

        if(!bHasModalPlugin){
            let r = window.confirm('@lang('elements::lang.delete_confirm_message')');
            if(r === true){
                deleteItem();
            }
        }
    }
    function onImageError(ele){
        ele.src = '{{asset('vendor/elements/img/default_user.png')}}';
    }

    function closestParent(el, selector, stopSelector) {
        if(!el) return null;
        if(!(el instanceof Element)) return null;
        let retval = null;
        while (el) {
            if (el.matches(selector)) {
                retval = el;
                break
            } else if (stopSelector && el.matches(stopSelector)) {
                break
            }
            el = el.parentElement;
        }
        return retval;
    }

    function deleteItem(){
        if(!window.currentItem) return;
        let form = document.getElementById('form-' + window.currentItem);
        if(!form) return;
        let r = new XMLHttpRequest(), fd = new FormData(form);
        let loading = document.querySelector('#deleteConfirmModal .modal-loading');
        if(loading){
            loading.classList.add('active');
        }

        r.onload = () => {
            if(r.readyState === 4){
                if(window.$ && typeof ($().modal) === 'function'){
                    $('#deleteConfirmModal').modal('hide');
                }
                
                if(loading){
                    loading.classList.remove('active');
                }
                
                if(r.status === 200){
                    let row = closestParent(form, 'tr', 'table');
                    (window.success || alert)('Delete item successful.');
                    if(row){
                        row.parentNode.removeChild(row);
                    }
                }
                else{
                    (window.error || alert)(`${r.status} Request failed.`);
                }
            }
        };
        r.onerror = (er) => (window.error || alert)('Something was wrong. Please try again later.');
        r.open('POST', form.action);
        r.setRequestHeader('Accept', 'application/json');
        r.send(fd);
    }

    function paginateLinkClicked(event){
        try{
            const href = event.target.tagName.toLowerCase() === 'a' ? event.target.href : event.target.closest('a').href;
            console.log(`paginate link click: ${href}`)
            history.pushState({href: href}, "", href);
            // reload table body
            const r = new XMLHttpRequest();
            r.onload = () => {
                if(r.readyState === 4 && r.status === 200){
                    const tbBody = event.target.closest('.table-responsive');
                    tbBody.innerHTML = r.responseText;
                    initPaginateClick();

                    if(typeof $ !== 'undefined' && $.material){
                        $.material.init();
                    }
                }
            }
            r.open('GET', href);
            r.setRequestHeader('page-type', 'table-content');
            r.send();
        }
        catch(err){
            console.log(err);
        }
        event.preventDefault();
        event.stopPropagation();
        return false;
    }

    function initPaginateClick(){
        document.querySelectorAll('.paginate ul.pagination li a').forEach(a => {
            a.addEventListener('click', paginateLinkClicked)
        });
    }
    window.addEventListener('load', () => {
        initPaginateClick();
    })
</script>
@endif
