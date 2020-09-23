<div class="row">
    @foreach($elements as $ele)
    <div class="col-md-{{12/ count($elements)}}">
        {!! $ele->render() !!}
    </div>
    @endforeach    
</div>