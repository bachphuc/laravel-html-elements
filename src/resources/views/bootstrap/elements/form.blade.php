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
        <button type="submit" class="btn btn-primary pull-right">Submit</button>
        @if(isset($cancelUrl) && !empty($cancelUrl))
        <a href="{{$cancelUrl}}" class="btn pull-right">Cancel</a>
        @endif
    </div>
    <div class="clearfix"></div>
</form> 