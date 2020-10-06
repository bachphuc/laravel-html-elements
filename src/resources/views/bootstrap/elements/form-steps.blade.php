@php
    $bShowSubmitButton = isset($show_submit_button) ? $show_submit_button : true;
@endphp
<form {!! isset($onsubmit) && !empty($onsubmit) ? ' onsubmit="' . $onsubmit . '" ' : '' !!} method="POST" action="{{isset($action) ? $action : ''}}" {{isset($has_file) && $has_file ? 'enctype=multipart/form-data' : ''}}>
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

<script>
    window.addEventListener('load', () => {
        document.querySelectorAll('.btn-next-tab').forEach(e => {
            e.addEventListener('click', handleNextTab)
        })

        function handleNextTab(e){
            const panel = e.target.closest('.tab-pane');
            if(panel){
                let pass = true;
                panel.querySelectorAll('input,select').forEach(i => {
                    if(pass){
                        const b = i.reportValidity();
                        if(!b){
                            pass = false;
                        }
                    }
                });
                if(pass){
                    if(e.target.dataset['next']){
                        $(`[href='#${e.target.dataset['next']}']`).tab('show');
                    }
                    else if(panel.nextElementSibling){
                        $(`[href='#${panel.nextElementSibling.id}']`).tab('show');
                    }
                }
            }
        }
    });
</script>