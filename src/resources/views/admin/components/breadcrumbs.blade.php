@if(isset($breadcrumbs) && !empty($breadcrumbs))
    <ol class="breadcrumb">
        @foreach($breadcrumbs as $key => $breadcrumb)
        <li class="{{isset($breadcrumb['active']) && $breadcrumb['active'] ? 'active' : ''}}">
            <a href="{{isset($breadcrumb['url']) ? $breadcrumb['url'] : ''}}">{{$breadcrumb['title']}}</a>
        </li>
        @endforeach
    </ol>
@endif