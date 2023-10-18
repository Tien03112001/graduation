@extends('theme.components.layout')
@section('content')

    @include('theme.components.breadcrumb',[
        'list' => [
            ['href' => '/', 'title' => 'Trang chủ'],
            ['title' => 'Tìm kiếm bài viết'],
        ]
    ])

    <section class="page-title-section">
        <div class="container">
            <div class="page-title-inner">
                <h2 class="page-title">
                    Tìm kiếm  bài viết
                </h2>
            </div>
        </div>
    </section>
    <section class="news-section">
        <div class="container">
            <div class="news-items-grid">
                @foreach($pagination as $post)
                    <div class="news-item">
                        <div class="news-item-thumb">
                            <a href="{{$post->full_path}}" class="news-item-image">
                                <img class="lazy" data-src="{{$post->image}}" alt="{{$post->alt??$post->name}}">
                            </a>
                        </div>
                        <div class="news-item-body">
                            <div class="news-item-info">
                                <h3 class="news-item-title">
                                    <a href="{{$post->full_path}}">{{$post->article->title}}</a>
                                </h3>
                                <p class="news-item-des">
                                    {!! $post->summary !!}
                                </p>
                            </div>
                            <div class="news-item-footer">
                                <a href="{{$post->article->author_url}}" class="news-item-author">
                                    <div class="news-item-author-image">
                                        <img src="/theme/App/images/users/user-1.webp" alt="user-1">
                                    </div>
                                    <div class="news-item-author-info">
                                        <div class="news-item-author-name">{{$post->article->author_name}}</div>
                                        <div class="news-item-author-date">{{$post->created_date}}</div>
                                    </div>
                                </a>
                                @if($post->category)
                                    <a href="{{$post->category->full_path}}"
                                       class="news-item-cat">{{$post->category->name}}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="new-pagiantion">
                @if($pagination->isNotEmpty())
                    <ul class="pagination">
                        @if($pagination->previousPageUrl())
                            <li class="pagination-item">
                                <a href="{{$pagination->previousPageUrl()}}"
                                   class="pagination-link pagination-link-prev pagination-link-disabled">
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.83962 2.06621V1.03094C8.83962 0.941211 8.7365 0.891658 8.66685 0.946568L2.62935 5.66219C2.57805 5.70209 2.53655 5.75317 2.50799 5.81154C2.47944 5.86992 2.4646 5.93404 2.4646 5.99902C2.4646 6.06401 2.47944 6.12813 2.50799 6.18651C2.53655 6.24488 2.57805 6.29596 2.62935 6.33585L8.66685 11.0515C8.73783 11.1064 8.83962 11.0568 8.83962 10.9671V9.93184C8.83962 9.86621 8.80882 9.80327 8.75792 9.76309L3.93649 5.99969L8.75792 2.23496C8.80882 2.19478 8.83962 2.13184 8.83962 2.06621Z"
                                              fill="rgba(0,0,0,.85"/>
                                    </svg>
                                </a>
                            </li>
                        @endisset
                        @for($i = 1; $i <= $pagination->lastPage(); $i++)
                            @if($pagination->currentPage()==$i)
                                <li class="pagination-item">
                                    <a href="{{$pagination->url($i)}}"
                                       class="pagination-link pagination-link-active">
                                        <span>{{$i}}</span>
                                    </a>
                                </li>
                            @else
                                <li class="pagination-item">
                                    <a href="{{$pagination->url($i)}}" class="pagination-link">
                                        <span>{{$i}}</span>
                                    </a>
                                </li>
                            @endif
                        @endfor
                        @if($pagination->nextPageUrl())
                            <li class="pagination-item">
                                <a href="{{$pagination->nextPageUrl()}}"
                                   class="pagination-link pagination-link-next">
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.39777 5.66281L3.36027 0.947189C3.34449 0.934768 3.32553 0.92705 3.30557 0.924919C3.2856 0.922788 3.26544 0.926332 3.2474 0.935143C3.22936 0.943955 3.21417 0.957676 3.20357 0.974732C3.19298 0.991787 3.18741 1.01149 3.1875 1.03156V2.06683C3.1875 2.13246 3.2183 2.1954 3.2692 2.23558L8.09063 6.00031L3.2692 9.76505C3.21697 9.80522 3.1875 9.86817 3.1875 9.9338V10.9691C3.1875 11.0588 3.29063 11.1083 3.36027 11.0534L9.39777 6.33781C9.44908 6.29779 9.4906 6.24658 9.51915 6.1881C9.5477 6.12962 9.56254 6.06539 9.56254 6.00031C9.56254 5.93523 9.5477 5.87101 9.51915 5.81253C9.4906 5.75404 9.44908 5.70284 9.39777 5.66281Z"
                                              fill="rgba(0,0,0,.85)"/>
                                    </svg>
                                </a>
                            </li>
                        @endisset

                    </ul>
                @endif
            </div>
        </div>
    </section>
@endsection

@section('scripts')
@endsection