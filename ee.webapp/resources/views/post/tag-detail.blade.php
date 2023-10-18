@extends('master')
@section('main')
    <div class="container">
        <section class="featured-posts">
            <div class="section-title">
                <h2><span>Thuộc tag: {{$tag->name}}</span></h2>
            </div>
            <div class="card-columns listfeaturedtag">
            @foreach($posts as $post)
                <!-- begin post -->
                    <div class="card">
                        <div class="row">
                            <div class="col-md-5 wrapthumbnail">
                                <a href="post.html">
                                    <div class="thumbnail" style="background-image:url({{$post->image_full_path}});">
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-7">
                                <div class="card-block">
                                    <h2 class="card-title"><a href="{{$post->full_path}}">{{$post->name}}</a></h2>
                                    <h4 class="card-text">{!! $post->summary !!}</h4>
                                    <div class="metafooter">
                                        <div class="wrapfooter">
                                    <span class="meta-footer-thumb">
                                    <a href="author.html"><img class="author-thumb" src="https://www.gravatar.com/avatar/e56154546cf4be74e393c62d1ae9f9d4?s=250&amp;d=mm&amp;r=x" alt="Sal"></a>
                                    </span>
                                            <span class="author-meta">
                                    <span class="post-name">{{$post->article->author_name}}</span><br/>
                                    <span class="post-date">{{$post->created_at->format('d-m-Y')}}</span><span class="dot"></span><span class="post-read">6 min read</span>
                                    </span>
                                            <span class="post-read-more"><a href="{{$post->full_path}}" title="Read Story"><svg class="svgIcon-use" width="25" height="25" viewbox="0 0 25 25"><path d="M19 6c0-1.1-.9-2-2-2H8c-1.1 0-2 .9-2 2v14.66h.012c.01.103.045.204.12.285a.5.5 0 0 0 .706.03L12.5 16.85l5.662 4.126a.508.508 0 0 0 .708-.03.5.5 0 0 0 .118-.285H19V6zm-6.838 9.97L7 19.636V6c0-.55.45-1 1-1h9c.55 0 1 .45 1 1v13.637l-5.162-3.668a.49.49 0 0 0-.676 0z" fill-rule="evenodd"></path></svg></a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end post -->
                @endforeach
            </div>
        </section>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                {{$posts->links()}}
            </ul>
        </nav>
        @include('block/footer')
    </div>
@endsection
