<!DOCTYPE html>
<html lang="{{ $appSettings['language'] ?? 'vi' }}">

<head>
    <style>
        body {
            color: #202020
        }
    </style>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <title>{{$page->meta->title??$page->name}} - {{$appSetting['brand_name']??config('app.name')}}</title>

    @isset($page->meta)
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
        @forelse($page->structures as $s)
            <script type="application/ld+json">{!! $s !!}}</script>
        @empty
        @endforelse
        @isset($page->amp_path)
            <link rel="amphtml" href="{{$page->amp_path}}">
        @endisset
    @endisset

    @yield('meta_data')
    @yield('structure_data')

    @yield('preload')
    <link rel="stylesheet" media="print" onload="this.onload=null;this.removeAttribute('media');"
          href="https://pro.fontawesome.com/releases/v5.2.0/css/all.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('theme/App/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/App/css/owl.theme.default.min.css') }}">

    <!-- very small file for critical CSS (or optionally inlined with a <style> block) -->
    <link rel="stylesheet" href="/critical.css">

    <!-- async non-critical CSS -->
    <link rel="stylesheet" media="print" onload="this.onload=null;this.removeAttribute('media');"
          href="{{ asset('theme/App/css/main.css') }}">

    <!-- no-JS fallback for non-critical CSS -->
    <noscript>
        <link rel="stylesheet" href="{{ asset('theme/App/css/main.css') }}">
    </noscript>

    @yield('styles')
<!-- async non-critical CSS -->
    <link rel="stylesheet" media="print" onload="this.onload=null;this.removeAttribute('media');"
          href="{{ asset('theme/App/css/media.css') }}">

    <!-- no-JS fallback for non-critical CSS -->
    <noscript>
        <link rel="stylesheet" href="{{ asset('theme/App/css/media.css') }}">
    </noscript>


    @section('embed_code_header')
        @foreach($headerCodes as $c)
            {!!$c!!}
        @endforeach
    @show

</head>

<body>

@include('theme.components.header')
@yield('content')
@include('theme.components.footer')
<script src="https://cdn.jsdelivr.net/npm/vanilla-lazyload@17.8.3/dist/lazyload.min.js"></script>
<script src="{{ asset('theme/App/js/jquery.min.js') }}" defer></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" defer></script>
<script src="{{ asset('theme/App/js/owl.carousel.min.js') }}" defer></script>
<script src="{{ asset('theme/App/js/main.js') }}"></script>
@yield('scripts')
@section('embed_code_body')
    @foreach($bodyCodes as $c)
        {!!$c!!}
    @endforeach
@show
</body>

</html>
