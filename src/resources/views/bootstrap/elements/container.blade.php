<div class="{{isset($fluid) && $fluid ? 'container-fluid' : 'container'}}">
    @if(isset($elements) && !empty($elements))
    @foreach($elements as $ele)
    {!! $ele->render() !!}
    @endforeach      
    @endif
</div>