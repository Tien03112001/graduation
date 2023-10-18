<footer class="ftco-footer ftco-bg-dark ftco-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md">
                <div class="ftco-footer-widget mb-4">
                    <h2 class="ftco-heading-2">{{$company_information['Tên dự án']}}</h2>
                    <p>{{$company_information['Slogan dự án']}}</p>
                    <p class="mt-4"><a href="{{$company_information['URL landing dự án']}}" class="btn btn-primary p-3">Bắt
                            đầu ngay</a></p>
                </div>
            </div>
            <div class="col-md">
                <div class="ftco-footer-widget mb-4 ml-md-5">
                    <h2 class="ftco-heading-2">Liên kết</h2>
                    <ul class="list-unstyled">
                        @foreach($menus['menu-link-huu-dung'] as $item)
                            <li><a href="{{$item['url']}}" class="py-2 d-block">{{$item['name']}}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md">
                <div class="ftco-footer-widget mb-4">
                    <h2 class="ftco-heading-2">Điều hướng</h2>
                    <ul class="list-unstyled">
                        @foreach($menus['menu-dau-trang'] as $item)
                            <li><a href="{{$item['url']}}" class="py-2 d-block">{{$item['name']}}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md">
                <div class="ftco-footer-widget mb-4">
                    <div class="block-23 mb-3">
                        <ul>
                            <li>
                                <span class="icon icon-map-marker"></span><span class="text">
                                {{$company_information['Địa chỉ']}}
                                </span>
                            </li>
                            <li><a href="tel:{{$company_information['SĐT']}}"><span class="icon icon-phone"></span><span
                                            class="text">{{$company_information['SĐT']}}</span></a></li>
                            <li><a href="mailto:{{$company_information['Email']}}"><span
                                            class="icon icon-envelope"></span><span
                                            class="text">{{$company_information['Email']}}</span></a>
                            </li>
                            <li><span class="icon icon-clock-o"></span><span
                                        class="text">{{$company_information['Giờ làm việc']??''}}</span>
                            </li>
                        </ul>
                    </div>
                    <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
                        <li class="ftco-animate"><a href="{{$company_information['Link twitter']??''}}"><span
                                        class="icon-twitter"></span></a></li>
                        <li class="ftco-animate"><a href="{{$company_information['Link facebook']??''}}"><span
                                        class="icon-facebook"></span></a></li>
                        <li class="ftco-animate"><a href="{{$company_information['Link instagram']??''}}"><span
                                        class="icon-instagram"></span></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">

                <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    Copyright &copy;<script>document.write(new Date().getFullYear());</script>
                    All rights reserved | This template is made with <i class="icon-heart" aria-hidden="true"></i> by <a
                            href="https://colorlib.com" target="_blank">Colorlib</a>
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
            </div>
        </div>
    </div>
</footer>