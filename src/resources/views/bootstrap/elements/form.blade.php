@push('scripts')
@foreach($requiredAssets as $path)
    @include($path)
@endforeach  
@endpush

@php
    $bShowSubmitButton = isset($show_submit_button) ? $show_submit_button : true;

    $ajax = isset($ajax) ? $ajax : false;
    $submitFunctionName = isset($onSubmit) ? $onSubmit : 'fn'. str_random(8);
    $submitFunction = "";
    if($ajax){
        $submitFunction = 'onsubmit="return '.$submitFunctionName . '(event);"';
    }
    $isUpdate = isset($isUpdate) ? $isUpdate : false;
    $submitText = isset($submitText) ? $submitText: ($isUpdate ? element_trans('lang.update') : element_trans('lang.submit'));
    $cancelText = isset($cancelText) ? $cancelText: element_trans('lang.cancel');
@endphp
<form {!! $submitFunction !!} {!! isset($onsubmit) && !empty($onsubmit) ? ' onsubmit="' . $onsubmit . '" ' : '' !!} method="POST" action="{{isset($action) ? $action : ''}}" {{isset($has_file) && $has_file ? 'enctype=multipart/form-data' : ''}}>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    @include('elements::'. $theme . '.manage.base.message')
    @php
        $method = isset($method) ? $method : 'POST';
    @endphp
    @if($method != 'POST' && $method != 'GET')
    <input type="hidden" name="_method" value="{{$method}}" />
    @endif
    @foreach($elements as $ele)
        {!! $ele->render() !!}
    @endforeach    
    <div class="text-right">
        @if(isset($cancelUrl) && !empty($cancelUrl))
        <a href="{{$cancelUrl}}" class="btn mr-2">{{$cancelText}}</a>
        @endif

        @if(isset($renderActionButtons) && $renderActionButtons && is_callable($renderActionButtons))
            {!! $renderActionButtons(isset($item) ? $item : null) !!}
        @endif

        @if($bShowSubmitButton)<button type="submit" class="btn btn-primary">{{$submitText}}</button>@endif
    </div>
    <div class="clearfix"></div>
</form> 

@foreach($elements as $ele)
    @if($ele->getType() == 'text_editor' && $ele->getAttribute('allow_upload_image'))
        @php
            $uploadUrl = $ele->getAttribute('upload_image_url');
        @endphp
        @if(!empty($uploadUrl))
        <form method="POST" enctype="multipart/form-data" id="form-{{$ele->getAttribute('name')}}" action="{{$uploadUrl}}" style="width: 2px; height: 2px;opacity: 0;overflow: hidden;">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <input type="file" name="files[]" id="form-{{$ele->getAttribute('name')}}-image-file" accept="image/png, image/jpeg" />
        </form>
        @endif
    @endif
@endforeach   

@if(isset($ajax) && $ajax)
<script>
    function {!! $submitFunctionName !!}(e){
        // update tinymce content first
        const tinymces = document.querySelectorAll('.tinymce-editor');
        if(tinymces.length){
            for(let i = 0; i < tinymces.length; i++){
                const editor = tinymce.get(tinymces[i].id);
                tinymces[i].value = editor.getContent();
            }
        }

        const o = new XMLHttpRequest();
        o.addEventListener("load", () => {
            if(o.readyState === 4){
                if(o.status === 200){
                    const d = JSON.parse(o.responseText);
                    if(d.status){
                        (window.success || alert)(d.message || '{{element_trans('lang.action_complete')}}');
                        @if(!$isUpdate)
                        e.target.reset();
                        @endif
                        @if(!is_modal_request())
                        if(d.redirect_url){
                            setTimeout(() => {
                                window.location.href = d.redirect_url;
                            }, 3000);
                        }
                        @endif
                    }
                    else
                        (window.error || alert)(d.message || 'Action failed');
                }
                else{
                    (window.error || alert)(`Something went wrong, please try again later. Status code: ${o.status}`);
                }
            }
        })

        o.open('POST', e.target.action);
        o.setRequestHeader('Accept', 'application/json');
        o.send(new FormData(e.target));

        e.preventDefault();
        e.stopPropagation();
        return false;
    }
</script>
@endif