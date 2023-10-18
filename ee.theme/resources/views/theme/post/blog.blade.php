@extends('theme.components.layout')
@section('styles')
    <link rel="stylesheet" media="print" onload="this.onload=null;this.removeAttribute('media');"
          href="{{ asset('theme/App/css/blog.css') }}">
@endsection
@section('content')
    @if (session('msg_success'))
        <div>
            <div class="alert alert-success" id="alert" onclick="this.parentElement.style.display='none';" role="alert">
                {{ session('msg_success') }}
            </div>
        </div>
    @endif
    @if (session('msg_error'))
        <div>
            <div class="alert alert-danger" id="alert" onclick="this.parentElement.style.display='none';" role="alert">
                {{ session('msg_error') }}
            </div>
        </div>
    @endif

    @include('theme.components.breadcrumb',['list'=>[['href'=>'/','title'=>'Trang chủ'],['title'=>'Blog']]])

    <!-- Blog page -->
    <div class="blog-page-wrapper mb-100 mb-sm-50 mb-md-60 mt-100 mt-xs-80">
        <div class=" container ">
            <div class="row ">
                <div class="post-left-sidebar col-lg-4 order-2 order-lg-1">
                    <div class="page-sidebar">
                        <!--======= single sidebar widget =======-->
                        <div class="single-sidebar-widget">
                            <h2 class="single-sidebar-widget--title">Nổi bật</h2>
                            <p>Tại đây, chúng tôi cập nhiều thông tin hữu ích về thời trang, thương hiệu trong nước và
                                quốc tế</p>
                        </div>

                        <div class="single-sidebar-widget">
                            <h2 class="single-sidebar-widget--title">Bài đăng gần đây</h2>
                            <div class="widget-post-wrapper">
                                @foreach ($postLastest->take(3) as $p)
                                    <div class="single-widget-post">
                                        <div class="image"><a
                                                    href="/post_categories/{{ $p->category_slug }}/posts/{{ $p->slug }}"><img
                                                        class="lazy" data-src="{{ $p->image }}" alt=""></a></div>
                                        <div class="content">
                                            <h3 class="widget-post-title"><a
                                                        href="/post_categories/{{ $p->category_slug }}/posts/{{ $p->slug }}">{{ $p->name }}</a>
                                            </h3>
                                            <p class="widget-post-date">{{ date('d/m/Y', strtotime($p->created_at)) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!--======= End of widget post wrapper =======-->
                        </div>
                    </div>
                </div>
                <div class=" col-lg-8 order-1 order-lg-2 mb-md-70 mb-sm-70">
                    <div class="row ">
                        @foreach ($pagination as $p)
                            <div class="post-bigblock col-lg-12 col-md-12 col-sm-6 col-12">
                                <div class="single-slider-post">
                                    <div class="single-slider-post__image mb-30">
                                        <a href="/post_categories/{{ $p->category_slug }}/posts/{{ $p->slug }}">
                                            <img id="" class="lazy" data-src="{{ $p->image }}">
                                        </a>
                                    </div>
                                    <div class="single-slider-post__content">
                                        <div class="post-info d-flex flex-wrap align-items-center mb-10">
                                            <div class="post-user pr-30"><i class="far fa-user-alt"></i>
                                                <p>Admin</p>
                                            </div>
                                            <div class="post-date mb-0 pr-30"><i class="far fa-calendar-alt"></i>
                                                <p>{{ date('d/m/Y', strtotime($p->created_at)) }}</p>
                                            </div>
                                            <div class="post-comment pr-30"><i class="far fa-comments"></i><a
                                                        href="/post_categories/{{ $p->category_slug }}/posts/{{ $p->slug }}">
                                                    {{ $p->public_comments ? count($p->public_comments) : '0' }} bình
                                                    luận</a>
                                            </div>
                                        </div>
                                        <h2 class="post-title"><a
                                                    href="post_categories/{{ $p->category_slug }}/posts/{{ $p->slug }}">{{ $p->name }}</a>
                                        </h2>
                                        <p class="post-excerpt">{!! $p->summary !!}</p> <a class="blog-readmore-btn"
                                                                                           href="post_categories/{{ $p->category_slug }}/posts/{{ $p->slug }}">Đọc
                                            Thêm</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="theme-default-pagination text-center">
                                <nav class="shop-pagination justify-content-center pagination">
                                    @if ($pagination->isNotEmpty())
                                        <ul class="">
                                            @if ($pagination->previousPageUrl())
                                                <li class=" disabled prev"><a style="cursor:pointer"
                                                                              onclick="setParamsPage('page','{{ (int) Request::get('page') - 1 }}')">
                                                        <span>Trước</span>
                                                    </a>
                                                </li>
                                            @endif
                                            @if (!$pagination->previousPageUrl())
                                                <li class=" disabled prev"><a style="cursor:not-allowed">
                                                        <span>Trước</span>
                                                    </a>
                                                </li>
                                            @endif
                                            @for ($i = 1; $i <= $pagination->lastPage(); $i++)
                                                @if ($pagination->currentPage() == $i)
                                                    <li class="active">
                                                        <a style="cursor:not-allowed">
                                                            <span>{{ $i }}</span>
                                                        </a>
                                                    </li>
                                                @else
                                                    <li>
                                                        <a style="cursor:pointer"
                                                           onclick="setParamsPage('page','{{ $i }}')">
                                                            <span>{{ $i }}</span>
                                                        </a>
                                                    </li>
                                                @endif
                                            @endfor
                                            @if ($pagination->nextPageUrl())
                                                <li class="next">
                                                    <a style="cursor:pointer"
                                                       onclick="setParamsPage('page','{{ (int) Request::get('page') + 1 }}')"
                                                       aria-hidden="true">
                                                        Sau
                                                    </a>
                                                </li>
                                            @endif
                                            @if (!$pagination->nextPageUrl())
                                                <li class="next">
                                                    <a style="cursor:not-allowed" aria-hidden="true">
                                                        Sau
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    @endif
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const urlParams = new URLSearchParams(window.location.search);

        function setParamsPage(name, value) {
            urlParams.set(name, value);
            window.location.search = urlParams;
        }
    </script>
@endsection
