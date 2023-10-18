<footer>
    <section class="container">
        <div class="ft d-flex justify-content-between">
            <div class="ft-column">
                <img class="lazy"
                    data-src="{{ $appSettings['footer_logo'] }}">
                <p class="copyright">Copyright © Lebro Shop</p>
                <p class="copyright">All Right Reserved.</p>
            </div>

            {{-- @foreach ($menus['Menu cuối trang'] as $key => $item)
                @if ($item['children'] != null)
                <div class="ft-column">
                    <h4>{{$item['name']}}</h4>
                    <ul>
                         @foreach ($item['children'] as $c)
                            <li><a href="javascript:;"> {!!$c['name']!!}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @endforeach --}}
            <div class="ft-column">
                <h4>hỗ trợ khách hàng</h4>

                <ul>
                    @foreach ($menus['Menu hỗ trợ khách hàng'] as $key => $item)
                        <li><a href="{{ $item['url'] }}">{{ $item['name'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="ft-column">
                <h4>danh mục</h4>
                <ul>
                    @foreach ($menus['Menu danh mục'] as $key => $item)
                        <li><a href="{{ $item['url'] }}">{{ $item['name'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="ft-column">
                <h4>kết nối với lebro</h4>
                <ul>
                    @foreach ($menus['Menu link liên kết'] as $key => $item)
                        @if ($item['name'] == 'Facebook')
                            <li>
                                <a href="{{ $item['url'] }}">
                                    <i class="fab fa-facebook-f"></i>
                                    <span>{{ $item['name'] }}</span>
                                </a>
                            </li>
                        @endif
                        @if ($item['name'] != 'Facebook')
                            <li>
                                <a href="{{ $item['url'] }}">
                                    <img class="ft-social-icon lazy"  data-src="{{ $item['icon'] }}">
                                    <span>{{ $item['name'] }}</span>
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>

            <div class="ft-column">
                <h3 class="ft-subscribe">Đăng ký</h3>
                <p>Để lại email để nhận nhiều thông tin ưu đãi</p>
                <form action="/forms" method="POST" class="newsletter">
                    <input type="hidden" name="name" value="Đăng ký nhận thông báo">
                    <input type="email" name="email" placeholder="Nhập email của bạn">
                    <button type="submit"><i class="fal fa-long-arrow-right"></i></button>
                    @csrf
                </form>
            </div>
        </div>
        <div class="design-by">
            <p>ATP - Thiết kế bởi Ezisolutions</p>
        </div>
    </section>
</footer>
