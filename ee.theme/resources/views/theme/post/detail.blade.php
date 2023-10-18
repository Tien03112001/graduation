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

    @include('theme.components.breadcrumb',[
        'list' => [
            ['href' => '/', 'title' => 'Trang chủ'],
            ['href' => $post->category->full_path, 'title' => $post->category->name],
            ['title' => $post->name],
        ]
    ])

    <!-- Blog page -->
    <div class="blog-page-wrapper mb-100 mb-sm-50 mb-md-60 mt-100 mt-xs-80">
        <div class=" container ">
            <div class="row ">
                <div class="post-left-sidebar col-lg-4 order-2 order-lg-1">
                    <div class="page-sidebar"> <!--======= single sidebar widget =======-->
                        <div class="single-sidebar-widget">
                            <h2 class="single-sidebar-widget--title">Bài đăng gần đây</h2>
                            <div class="widget-post-wrapper">
                                @foreach ($related_posts as $p)
                                    <div class="single-widget-post">
                                        <div class="image"><a
                                                    href="/post_categories/{{ $p->category_slug }}/posts/{{ $p->slug }}"><img
                                                        src="{{ $p->image }}" alt=""></a></div>
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
                        <div class="post-bigblock col-lg-12 col-md-12 col-sm-6 col-12">
                            <div class="single-slider-post detail-post">
                                @if (isset($post))
                                    <div class="single-slider-post__image">
                                        <a href="javascript:;">
                                            <img id="" class="lazyautosizes lazyloaded" src="{{$post->image}}">
                                        </a>
                                    </div>
                                    <div class="single-slider-post__content">
                                        <div class="post-info d-flex flex-wrap align-items-center mb-10">
                                            <h2 class="post-title">
                                                <a>{{$post->name}}</a>
                                            </h2>
                                            <div class="post-info d-flex flex-wrap align-items-center">
                                                <div class="post-user pr-30"><i class="far fa-user-alt"></i>
                                                    <p>Admin</p>
                                                </div>
                                                <div class="post-date mb-0 pr-30"><i class="far fa-calendar-alt"></i>
                                                    <p>{{ date('d/m/Y', strtotime($p->created_at)) }}</p>
                                                </div>
                                                <div class="post-comment pr-30"><i class="far fa-comments"></i>
                                                    <p>{{ $post->public_comments ? count($post->public_comments) : '0' }}
                                                        bình luận</p>
                                                </div>
                                            </div>

                                            <div class="single-blog-post-section">
                                                @if (isset($post->article->content))
                                                    {!!$post->article->content!!}
                                                @endif
                                            </div>
                                            <div class="detail-comment-mb">
                                                <div class="blog-detail-comment col-lg-12">
                                                    <h2 class="comment-title">Để lại bình luận của bạn</h2>
                                                    <div class="custom-lebro-form comment-form">
                                                        <form action="/comments" method="POST">
                                                            <input type="text" name="article_id"
                                                                   value="{{ $post->article->id }}" hidden>
                                                            <div class="custom-lebro-form comment-form">
                                                                <form action="/comments" method="POST">
                                                                    <input type="text" name="article_id"
                                                                           value="{{ $post->article->id }}" hidden>
                                                                    <div class="row">
                                                                        <div class="col-12"></div>
                                                                        <div class="comment-input col-md-6 mb-40"><input
                                                                                    type="text"
                                                                                    required=""
                                                                                    placeholder="Tên của bạn *" class=""
                                                                                    name="authorM" id="ContactFormName"
                                                                                    value="">
                                                                        </div>
                                                                        <div class="comment-input col-md-6 mb-40"><input
                                                                                    type="email"
                                                                                    required=""
                                                                                    placeholder="Địa chỉ email *"
                                                                                    class=""
                                                                                    name="emailM" id="ContactFormEmail"
                                                                                    value="">
                                                                        </div>
                                                                        <div class="comment-input col-lg-12 mb-40">
                                                                            <textarea placeholder="Bình luận của bạn *"
                                                                                      class="custom-textarea"
                                                                                      name="contentM"
                                                                                      id="ContactFormMessage"></textarea>
                                                                        </div>
                                                                        <div class="comment-input col-lg-12 text-center">
                                                                            <button
                                                                                    type="submit"
                                                                                    class="btn-custom">GỬI
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    @csrf
                                                                </form>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="customer-dcomment">
            <div class="review-content">
                <ul class="comment-list">
                    @foreach ($post->public_comments as $c)
                        <li class="review as-comment-item">
                            <div class="as-post-comment">
                                <div class="comment-avater"><img class="lazy"
                                                                 data-src="https://cdn-icons-png.flaticon.com/512/1077/1077114.png"
                                                                 alt="Comment Author"></div>
                                <div class="comment-content">
                                    <h4 class="name">{{ explode(",",$c->author)[0] }}</h4><span class="commented-on"><i
                                                class="fal fa-calendar-alt"></i>{{ date('d/m/Y', strtotime($c->created_at)) }}</span>
                                    <div class="star-rating" role="img" aria-label="Rated 5.00 out of 1">
                                        <span style="width:100%"></span>
                                    </div>
                                    <p class="text">{{ $c->content }}</p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        {{-- <div class="detail-comment-pc row"> <div class="blog-detail-comment col-lg-12"> <h2 class="comment-title">Để lại bình luận của bạn</h2> <form action="/comments" method="POST"> <div class="dt-up-comment"> <input type="text" name="article_id" value="14" hidden=""> <input type="text" name="author" placeholder="Tên của bạn"> <input type="email" name="email" placeholder="Địa chỉ email"> </div> <div class="dt-down-comment"> <textarea placeholder="Bình luận của bạn" name="content"></textarea> </div> <div class="dt-btn-comment"> <button class="btn-custom" type="submit">gửi</button> </div> <input type="hidden" name="_token" value="s6OMrs1RjTvBwmNVpSINVIRAatpaZpJ8sqppoJJz"> </form> </div> </div> --}}

        @if ($post->article!=null)
            <div class="detail-comment-pc row">
                <div class="blog-detail-comment col-lg-12">
                    <h2 class="comment-title">Để lại bình luận của bạn</h2>
                    <form action="/comments" method="POST">
                        <div class="dt-up-comment">
                            <input type="text" name="article_id" value="{{ $post->article->id }}" hidden>
                            <input type="text" name="author" placeholder="Tên của bạn">
                            <input type="email" name="email" placeholder="Địa chỉ email">
                        </div>
                        <div class="dt-down-comment">
                            <textarea placeholder="Bình luận của bạn" name="content"></textarea>
                        </div>
                        <div class="dt-btn-comment">
                            <button class="btn-custom" type="submit">gửi</button>
                        </div>
                        @csrf
                    </form>
                </div>
            </div>
        @endif
    </div>
    <!-- End blog page -->
@endsection

@section('scripts')
@endsection



