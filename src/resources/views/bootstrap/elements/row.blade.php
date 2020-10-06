<div class="row">
    @if(isset($elements) && !empty($elements))
        @foreach($elements as $ele)
        {!! $ele->render() !!}
        @endforeach      
    @endif
</div>
