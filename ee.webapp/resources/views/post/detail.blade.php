@extends('master')
@section('main')
    <!-- Begin Article
================================================== -->
    <div class="container">
        <div class="row">

            <!-- Begin Fixed Left Share -->
            <div class="col-md-2 col-xs-12">
            </div>
            <!-- End Fixed Left Share -->

            <!-- Begin Post -->
            <div class="col-md-8 col-md-offset-2 col-xs-12">
                <div class="mainheading">
                    <h1 class="posttitle">{{$post->name}}</h1>
                </div>
                <!-- Begin Post Content -->
                <div class="article-post">
                    {!! $post->article->content !!}
                </div>
                <!-- End Post Content -->

                <!-- Begin Tags -->
                <div class="after-post-tags">
                    <ul class="tags">
                        @foreach($post->tags as $tag)
                            <li><a href="{{$tag->full_path}}">{{$tag->name}}</a></li>
                        @endforeach
                    </ul>
                </div>
                <!-- End Tags -->
            </div>
            <!-- End Post -->

        </div>
    </div>
    <!-- End Article
    ================================================== -->
    @include('post/related')
    @include('post/comment')
    @include('block/footer')
@endsection
