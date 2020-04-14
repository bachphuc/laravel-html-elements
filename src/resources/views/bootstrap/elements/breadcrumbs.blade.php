@if(isset($breadcrumbs) && !empty($breadcrumbs))
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        @foreach($breadcrumbs as $key => $breadcrumb)
        <li class="breadcrumb-item {{isset($breadcrumb['active']) && $breadcrumb['active'] ? 'active' : ''}}"><a href="{{isset($breadcrumb['url']) ? $breadcrumb['url'] : ''}}">{{$breadcrumb['title']}}</a></li>
        @endforeach
    </ol>
</nav>
@endif