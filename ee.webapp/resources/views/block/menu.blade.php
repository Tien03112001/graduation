<!-- Begin Menu -->
<ul class="navbar-nav ml-auto">
    @foreach($menus['menu-dau-trang'] as $m)
        <li class="nav-item active">
            <a class="nav-link" href="{{$m['url']}}">{{$m['name']}}</a>
        </li>
    @endforeach
</ul>
<!-- End Menu -->
