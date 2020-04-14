@php
    $bUseAjax = isset($use_ajax) ? $use_ajax : false;
    $submitFunctionName = isset($onSubmit) ? $onSubmit : 'fn'. str_random(8);
    $submitFunction = "";
    if($bUseAjax){
        $submitFunction = 'onsubmit="'.$submitFunctionName . '(this);return false;"';
    }
@endphp

@if (count($errors) > 0)
<div>
    @foreach ($errors->all() as $error)
    <div notify error>{{ $error }}</div>
    @endforeach
</div>
@endif

<form {!! $submitFunction !!} method="POST" action="{{isset($action) ? $action : ''}}" {{isset($has_file) && $has_file ? 'enctype=multipart/form-data' : ''}}>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    @php
        $method = isset($method) ? $method : 'POST';
    @endphp
    @if($method != 'POST' && $method != 'GET')
    <input type="hidden" name="_method" value="{{$method}}" />
    @endif
    @foreach($elements as $ele)
        {!! $ele->render() !!}
    @endforeach    

    <div mt mt48>
        @if(isset($cancelUrl) && !empty($cancelUrl))
        <a href="{{$cancelUrl}}" btn="flat">@lang('lang.cancel')</a>
        @endif
        <button btn="primary" type="submit"><i class="material-icons">add</i> @lang('lang.submit') <span progress></span></button>
    </div>
</form> 

@if($bUseAjax)

@if(isset($scripts) && !empty($scripts))

@include($scripts, ['onsubmit' => $submitFunctionName])

@else
<script>
    function {!! $submitFunctionName !!}(e){
        var b = e.querySelector('button[type=submit]');
        b.disabled=true;
        var p=b.querySelector('[progress]');
        var r = new XMLHttpRequest();r.upload.onprogress=(e)=>{
            var c=e.lengthComputable?(Math.floor(e.loaded*100/e.total)):0;p.style.width=c+'%';
        };
        r.onload=(e)=>{
            if(r.readyState == 4 && r.status==200){
                var d = JSON.parse(r.responseText);
                if(d.status){
                    if(d.http_code==301&&d.url) window.location.href=d.url;
                } else{
                    (window.notify||alert)(d.message);b.disabled=false;
                }
            }
        };
        r.open('POST',e.action+(e.action.indexOf('?')===-1?'?':'&')+'format=json');
        r.send(new FormData(e));
        return false;
    }
</script>
@endif

@endif