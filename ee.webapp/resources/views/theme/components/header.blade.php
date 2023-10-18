<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" href="/">EziWeb.</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
                aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu"></span> Menu
        </button>

        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto">
                @foreach($menus['menu-dau-trang'] as $item)
                    <li class="nav-item"><a href="{{$item['url']}}" class="nav-link">{{$item['name']}}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>