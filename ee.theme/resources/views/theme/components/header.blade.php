<header>
    <div id="top">
        <div class="topbar">
            <div class="d-flex justify-content-between">
                <a href="tel:0859799889">
                    <p>Đặt hàng online gọi ngay {{$companyInformation['Điện thoại']}}</p>
                </a>
                <div class="topbar-icon">
                    <ul class="nav">
                        {{-- <li class="nav-item">
                            <a class="nav-link" href="{{$companyInformation['Fanpage']}}"><i
                                    class="fab fa-facebook-f"></i></a>
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{$companyInformation['Kênh Tiktok']}}">
                                <img src="{{ asset('theme/App/img/logo/tiktok.svg') }}">
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{$companyInformation['Kênh Shopee']}}">
                                <img src="{{ asset('theme/App/img/logo/shopee.svg') }}">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="sticky-top main-header">
        <div class="header-pc">
            <div class="d-flex justify-content-between">
                <a href="/"><img class="head-logo-pc"
                    src="{{ $appSettings['header_logo'] }}"
                        alt="Lebro"></a>
                <ul class="menu nav nav-pills">
                    @foreach ($menus['Menu đầu trang'] as $key => $item)
                        @if ($item['children'] == null and $item['parent_id'] == 0)
                            <li class="nav-item"><a class="nav-link" href="{{ $item['url'] }}">{{ $item['name'] }}</a>
                            </li>
                        @endif
                        @if ($item['children'] != null)
                            <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
                                    href="{{ $item['url'] }}">{{ $item['name'] }}</a>
                                <ul class="dropdown-menu">
                                    @foreach ($item['children'] as $c)
                                        <li><a class="dropdown-item" href="{{ $c['url'] }}">{{ $c['name'] }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @endforeach
                </ul>
                <ul class="head-icon nav">
                    <li class="pc-search nav-item dropdown">
                        <a class="pcsearch nav-link dropdown-toggle" data-bs-toggle="dropdown" href="javascript:;">
                            <i class="fal fa-search"></i>
                        </a>
                        <form action="/product_search">
                            <div class="dropdown-menu">
                                <input class="searchdrop dropdown-item" type="text" name="search"
                                    placeholder="Tìm kiếm">
                                <button type="submit"
                                    style="outline: 0;border: none;background: none;">
                                    <i class="fal fa-arrow-right"></i>
                                </button>
                            </div>
                        </form>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:;"><i class="fal fa-heart"></i></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/cart"><i class="fal fa-shopping-cart"></i></a>
                    </li>
                    {{-- <li class="nav-item">
                        {{$cart}}
                    </li> --}}

                    <style type="text/css">
                        .nav-link .fa-shopping-cart::after {
                            content: "{!! \App\Utils\CartUtil::getInstance()->getProductCount() !!}";
                            font-family: var(--body-font);
                            font-size: 12px;
                            position: absolute;
                            top: 0;
                            right: 2px;
                            padding: 2px 5px;
                            border-radius: 100%;
                            color: var(--white-color);
                            background: var(--theme-color);
                        }
                    </style>
                </ul>
            </div>
        </div>
        <div class="header-mobile">
            <div class="d-flex justify-content-between">

                <img class="head-logo-mobile"
                src="{{ $appSettings['mobile_header_logo'] }}"
                        alt="Lebro" onclick="redirectHome()">
                <ul class="head-icon nav">
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:;" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#sideNav"><i class="fal fa-search"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:;"><i class="fal fa-heart"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/cart"><i class="fal fa-shopping-cart"></i></a>
                    </li>
                </ul>
                <div class="navside offcanvas offcanvas-end" id="sideNav">
                    <div class="offcanvas-header">
                        <a href="/"><img class="logo-mobile"
                            src="{{ $appSettings['mobile_header_logo'] }}"
                                alt="Lebro"></a>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                    </div>
                    <form action="/product_search" class="searchsidenav">
                        <input class="sidenavinput" type="text" name="search" placeholder="Tìm kiếm">
                        <button type="submit"
                            style="outline: 0;border: none;background: none;" >
                            <i class="fal fa-arrow-right"></i>
                        </button>
                    </form>
                    <div class="offcanvas-body">
                        <div id="accordion" class="menuMobilelist">
                            @foreach ($menus['Menu đầu trang'] as $key => $item)
                                @if ($item['children'] == null and $item['parent_id'] == 0)
                                    <a href="{{ $item['url'] }}" class="menu-mobile-btn">
                                        {{ $item['name'] }}
                                    </a>
                                @endif
                                @if ($item['children'] != null)
                                    <a class="menu-mobile-btn has-children" data-bs-toggle="collapse"
                                        href="#collapseMenu">
                                        {{ $item['name'] }}
                                    </a>
                                    <div id="collapseMenu" class="collapse" data-bs-parent="#accordion">
                                        <div class="card-body">
                                            @foreach ($item['children'] as $c)
                                                <a href="{{ $c['url'] }}">{{ $c['name'] }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                        </div>
                    </div>
                </div>

                <button class="menu-mobile-icon" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#sideNav">
                    <i class="fal fa-bars"></i>
                </button>
            </div>
        </div>
    </div>
</header>
