<div class="col-{{$class}}">
    @if(isset($elements) && !empty($elements))
    <div>
        @foreach($elements as $ele)
        {!! $ele->render() !!}
        @endforeach      
    </div>  
    @endif
</div>
