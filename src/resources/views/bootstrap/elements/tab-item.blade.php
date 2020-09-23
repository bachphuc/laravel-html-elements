<li class="{{isset($active) && $active ? 'active' : ''}}">
    <a href="#{{isset($id) ? $id : ''}}" data-toggle="tab">
        @if(isset($icon))<i class="material-icons">{{$icon}}</i>@endif
        {{isset($title) ? $title : ''}}
    <div class="ripple-container"></div></a>
</li>