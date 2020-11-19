@php
    $bShowSubmitButton = isset($show_submit_button) ? $show_submit_button : true;

    $ajax = isset($ajax) ? $ajax : false;
    $submitFunctionName = isset($onSubmit) ? $onSubmit : 'fn'. str_random(8);
    $submitFunction = "";
    if($ajax){
        $submitFunction = 'onsubmit="return '.$submitFunctionName . '(event);"';
    }
@endphp
<form {!! $submitFunction !!} {!! isset($onsubmit) && !empty($onsubmit) ? ' onsubmit="' . $onsubmit . '" ' : '' !!} method="POST" action="{{isset($action) ? $action : ''}}" {{isset($has_file) && $has_file ? 'enctype=multipart/form-data' : ''}}>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    @include('bachphuc.elements::'. $theme . '.manage.base.message')
    @php
        $method = isset($method) ? $method : 'POST';
    @endphp
    @if($method != 'POST' && $method != 'GET')
    <input type="hidden" name="_method" value="{{$method}}" />
    @endif
    @foreach($elements as $ele)
        {!! $ele->render() !!}
    @endforeach    
    <div>
        @if(isset($renderActionButtons) && $renderActionButtons && is_callable($renderActionButtons))
            {!! $renderActionButtons(isset($item) ? $item : null) !!}
        @endif
        @if($bShowSubmitButton)<button type="submit" class="btn btn-primary pull-right">Submit</button>@endif
        @if(isset($cancelUrl) && !empty($cancelUrl))
        <a href="{{$cancelUrl}}" class="btn pull-right">Cancel</a>
        @endif
    </div>
    <div class="clearfix"></div>
</form> 

@if(isset($ajax) && $ajax)
<script>
    function {!! $submitFunctionName !!}(e){
        const o = new XMLHttpRequest();
        o.addEventListener("load", () => {
            if(o.readyState === 4){
                if(o.status === 200){
                    const d = JSON.parse(o.responseText);
                    if(d.status){
                        (window.success || alert)(d.message || 'Action completed.');
                        e.target.reset();
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