<ul class="nav">
    @if(isset($menus))
    @foreach($menus as $menu)
    @php
        $bActive = (isset($activeMenu) && $menu['key'] == $activeMenu) || (!isset($activeMenu) && $menu['key'] == 'dashboard');
        $bHasSubMenu = isset($menu['subs']) && !empty($menu['subs']) ? true : false;
    @endphp
    <li class="{{$bActive && !$bHasSubMenu ? 'active' : ''}}">
        @if($bHasSubMenu)
        <a class="nav-link" data-toggle="collapse" href="#menu_item_collapse_{{$menu['key']}}" aria-expanded="true">
            <i class="material-icons">{{$menu['icon']}}</i>
            <p>{{$menu['title']}}<b class="caret"></b></p>
        </a>

        <div id="menu_item_collapse_{{$menu['key']}}" class="collapse {{$bActive ? 'in' : ''}}">
            <ul class="nav">
                @foreach($menu['subs'] as $subMenu)
                <li class="{{(isset($subActiveMenu) && $subMenu['key'] == $subActiveMenu) ? 'active' : ''}}">
                    <a href="{{isset($subMenu['url']) ? $subMenu['url'] : '#'}}">
                        <i class="material-icons">{{$subMenu['icon']}}</i>
                        <p>{{$subMenu['title']}}</p>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        @else
        <a href="{{$menu['url']}}">
            <i class="material-icons">{{$menu['icon']}}</i>
            <p>{{$menu['title']}}</p>
        </a>
        @endif
    </li>
    @endforeach
    @endif
</ul>