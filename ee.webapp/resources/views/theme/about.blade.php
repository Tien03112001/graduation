@extends('theme.components.layout')
@section('title',isset($page->meta)?$page->meta->title:'')
@if(isset($page->meta))
    @push('meta_data')
        @isset ($page->meta->description)
            <meta name="description" content="{{$page->meta->description}}">
        @endisset
        @isset ($page->meta->keywords)
            <meta name="keywords" content="{{$page->meta->keywords}}">
        @endisset
        @isset ($page->meta->robots)
            <meta name="robots" content="{{$page->meta->robots}}">
        @endisset
        @isset ($page->meta->canonical)
            <link rel="canonical" href="{{$page->meta->canonical}}"/>
        @endisset
    @endpush
@endif

@section('styles')
@endsection

@section('structure_data')
    @foreach($page->structured_datas as $s)
        <script type="application/ld+json">
                {!! $s->content !!}}
        </script>
    @endforeach
@endsection

@section('content')
    @include('theme.components.banner')
    @foreach($blocks as $block)
        {!! $block !!}
    @endforeach
@endsection

@section('scripts')
@endsection