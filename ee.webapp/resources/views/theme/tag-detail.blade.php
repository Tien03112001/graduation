@extends('theme.components.layout')
@section('title',isset($tag->meta)?$tag->meta->title:'')
@if(isset($tag->meta))
    @push('meta_data')
        @isset ($tag->meta->description)
            <meta name="description" content="{{$tag->meta->description}}">
        @endisset
        @isset ($tag->meta->keywords)
            <meta name="keywords" content="{{$tag->meta->keywords}}">
        @endisset
        @isset ($tag->meta->robots)
            <meta name="robots" content="{{$tag->meta->robots}}">
        @endisset
        @isset ($tag->meta->canonical)
            <link rel="canonical" href="{{$tag->meta->canonical}}"/>
        @endisset
    @endpush
@endif

@section('styles')
@endsection

@section('structure_data')
    @forelse($tag->structured_datas as $s)
        <script type="application/ld+json">
            {!! $s->content !!}}
        </script>
    @empty
    @endforelse
@endsection

@section('content')
    @include('theme.components.banner')

    <section class="ftco-section bg-light">
        <div class="container">
            <div class="row">
                @foreach($pagination->items() as $index=>$post)
                    <div class="col-md-4 ftco-animate" data-aos-delay="{{$index * 100}}">
                        <div class="blog-entry">
                            <a href="{{$post->full_path}}" class="block-20"
                               style="background-image: url({{asset('theme/images/image_1.jpg')}});">
                            </a>
                            <div class="text p-4 d-block">
                                <div class="meta mb-3">
                                    <div><a href="{{$post->full_path}}">{{$post->created_date}}</a></div>
                                    <div><a href="{{$post->full_path}}">{{$post->article->author_name}}</a></div>
                                    <div><a href="{{$post->full_path}}" class="meta-chat">
                                            <span class="icon-chat"></span> {{$post->article->comments_count}}</a></div>
                                </div>
                                <h3 class="heading">
                                    <a href="{{$post->full_path}}">
                                        {{$post->article->title}}
                                    </a>
                                </h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row mt-5">
                <div class="col text-center">
                    <div class="block-27">
                        @if($pagination->isNotEmpty())
                            <ul>
                                @if($pagination->previousPageUrl())
                                    <li><a href="{{$pagination->previousPageUrl()}}">&lt;</a></li>
                                @endisset
                                @for($i = 1; $i <= $pagination->lastPage(); $i++)
                                    @if($pagination->currentPage()==$i)
                                        <li class="active">
                                            <span>{{$i}}</span>
                                        </li>
                                    @else
                                        <li>
                                            <a href="{{$pagination->url($i)}}">{{$i}}</a>
                                        </li>
                                    @endif
                                @endfor
                                @if($pagination->nextPageUrl())
                                    <li><a href="{{$pagination->nextPageUrl()}}">&gt;</a></li>
                                @endisset
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
@endsection


