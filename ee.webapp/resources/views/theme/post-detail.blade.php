@extends('theme.components.layout')
@section('title',isset($meta)?$meta->title:'')
@if(isset($page->meta))
    @push('meta_data')
        @isset ($meta->description)
            <meta name="description" content="{{$meta->description}}">
        @endisset
        @isset ($meta->keywords)
            <meta name="keywords" content="{{$meta->keywords}}">
        @endisset
        @isset ($meta->robots)
            <meta name="robots" content="{{$meta->robots}}">
        @endisset
        @isset ($meta->canonical)
            <link rel="canonical" href="{{$meta->canonical}}"/>
        @endisset
    @endpush
@endif

@section('styles')
@endsection

@section('structure_data')
@endsection

@section('content')
    @include('theme.components.banner')

    <section class="ftco-section ftco-degree-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-8 ftco-animate">
                    <h2 class="mb-3">{{$post->article->title}}</h2>
                    <p>
                        <span>Danh mục: <a href="{{$post->category->full_path}}">{{$post->category->name}}</a></span>
                        <span style="float: right;">Người viết: {{$post->article->author_name}}</span>
                    </p>
                    {!!  $post->article->content !!}
                    <div class="tag-widget post-tag-container mb-5 mt-5">
                        <div class="tagcloud">
                            @foreach($post->tags as $t)
                                <a href="{{$t->full_path}}" class="tag-cloud-link">{{$t->name}}</a>
                            @endforeach
                        </div>
                    </div>


                    <div class="pt-5 mt-5">
                        <h3 class="mb-5">{{$post->comments_count}} bình luận</h3>
                        <ul class="comment-list">
                            @foreach($post->comments as $c)
                                <li class="comment">
                                    <div class="vcard bio">
                                        <img src="theme/images/person_1.jpg" alt="avatar của {{$c->author}}">
                                    </div>
                                    <div class="comment-body">
                                        <h3>{{$c->author}}</h3>
                                        <div class="meta">{{$c->created_at}}</div>
                                        <p>{{$c->content}}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <!-- END comment-list -->

                        <div class="comment-form-wrap pt-5">
                            <h3 class="mb-5">Để lại bình luận</h3>
                            <form action="/binh-luan" class="p-5 bg-light" method="POST">
                                {{csrf_field()}}
                                <input name="article_id" value="{{$post->article->id}}" type="hidden"/>
                                <div class="form-group">
                                    <label for="author">Tên *</label>
                                    <input type="text" class="form-control" id="author" name="author">
                                </div>
                                <div class="form-group">
                                    <label for="content">Nội dung</label>
                                    <textarea name="content" id="content" cols="30" rows="5"
                                              class="form-control"></textarea>
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Gửi" class="btn py-3 px-4 btn-primary">
                                </div>
                            </form>
                        </div>
                    </div>

                </div> <!-- .col-md-8 -->
                <div class="col-md-4 sidebar ftco-animate">
                    <div class="sidebar-box">
                        <form action="/tim-kiem" class="search-form" method="GET">
                            {{csrf_field()}}
                            <div class="form-group">
                                <span class="icon fa fa-search"></span>
                                <input type="text" name="search" class="form-control"
                                       placeholder="Nhập từ khóa cần tìm">
                            </div>
                        </form>
                    </div>
                    <div class="sidebar-box ftco-animate">
                        <div class="categories">
                            <h3>Danh mục bài viết</h3>
                            @foreach($categories as $c)
                                <li><a href="{{$c->full_path}}">{{$c->name}} <span>({{$c->posts_count}})</span></a></li>
                            @endforeach
                        </div>
                    </div>

                    <div class="sidebar-box ftco-animate">
                        <h3>Bài viết liên quan</h3>
                        @foreach($related_posts as $p)
                            <div class="block-21 mb-4 d-flex">
                                <a class="blog-img mr-4"
                                   style="background-image: url('{{$p->image_full_path}}');"></a>
                                <div class="text">
                                    <h3 class="heading">
                                        <a href="{{$p->full_path}}">{{$p->article->title}}</a>
                                    </h3>
                                    <div class="meta">
                                        <div>
                                            <a href="{{$p->full_path}}">
                                                <span class="icon-calendar"></span> {{$p->created_date}}
                                            </a>
                                        </div>
                                        <div>
                                            <a href="{{$p->full_path}}">
                                                <span class="icon-person"></span> {{$p->article->author_name}}
                                            </a>
                                        </div>
                                        <div>
                                            <a href="{{$p->full_path}}">
                                                <span class="icon-chat"></span> {{$p->comments_count}}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="sidebar-box ftco-animate">
                        <h3>Tất cả thẻ</h3>
                        <div class="tagcloud">
                            @foreach($tags as $t)
                                <a href="{{$t->full_path}}" class="tag-cloud-link">{{$t->name}}</a>
                            @endforeach
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>
@endsection

@section('scripts')
@endsection



