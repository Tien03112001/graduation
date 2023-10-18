@extends('theme.components.layout')
@section('content')
    <section class="page-title-section">
        <div class="container">
            <div class="page-title-inner">
                <h2 class="page-title">
                    {{$pageDetail->name}}
                </h2>
            </div>
        </div>
    </section>
    <section class="news-section">
        <div class="container">
            {!! $pageDetail->view_html !!}
        </div>
    </section>
    @isset($pageDetail->article)
        <section class="news-section">
            <div class="container">
                {!! $pageDetail->article->formatted_content !!}
            </div>
        </section>
    @endisset
@endsection