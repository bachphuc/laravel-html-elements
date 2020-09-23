<div class="tab-pane {{isset($active) && $active ? 'active' : ''}}" id="{{isset($id) && $id ? $id : ''}}">
    @if(isset($elements))
    @foreach($elements as $ele)
    {!! $ele->render() !!}
    @endforeach    
    @endif
</div>