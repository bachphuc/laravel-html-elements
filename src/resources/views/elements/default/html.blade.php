@if(isset($view) && !empty($view))
@include($view)
@elseif(isset($content))
<div>
    {!! $content !!}
</div>
@endif