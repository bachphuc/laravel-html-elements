@if(isset($text))
<{{$tag}} {!! $class ? 'class="'. $class .'"' : '' !!} {!! $id ? 'id="'. $id .'"' : '' !!}>{!! $text !!}</{{$tag}}>
@endif