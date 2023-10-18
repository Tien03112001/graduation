<!DOCTYPE html>
<html lang="{{$lang ?? 'vi'}}">
<head>
    <title>@yield('title') - {{env('APP_NAME')}}</title>

    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @stack('meta_data')

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('theme/css/open-iconic-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('theme/css/animate.css')}}">
    <link rel="stylesheet" href="{{asset('theme/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('theme/css/owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{asset('theme/css/magnific-popup.css')}}">
    <link rel="stylesheet" href="{{asset('theme/css/aos.css')}}">
    <link rel="stylesheet" href="{{asset('theme/css/ionicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('theme/css/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{asset('theme/css/jquery.timepicker.css')}}">
    <link rel="stylesheet" href="{{asset('theme/css/flaticon.css')}}">
    <link rel="stylesheet" href="{{asset('theme/css/icomoon.css')}}">
    <link rel="stylesheet" href="{{asset('theme/css/style.css')}}">

    <link rel="icon" type="image/x-icon" href="{{asset('images/favicon.ico')}}">
    @yield('styles')
    @yield('structure_data')
    @section('embed_code_header')
        @foreach($header_embed_codes as $c)
            {{$c}}
        @endforeach
    @show
</head>
<body>
@include('theme.components.header')

@yield('content')


@include('theme.components.footer')

@include('theme.components.loader')

<script src="{{asset('theme/js/jquery.min.js')}}"></script>
<script src="{{asset('theme/js/jquery-migrate-3.0.1.min.js')}}"></script>
<script src="{{asset('theme/js/popper.min.js')}}"></script>
<script src="{{asset('theme/js/bootstrap.min.js')}}"></script>
<script src="{{asset('theme/js/jquery.easing.1.3.js')}}"></script>
<script src="{{asset('theme/js/jquery.waypoints.min.js')}}"></script>
<script src="{{asset('theme/js/jquery.stellar.min.js')}}"></script>
<script src="{{asset('theme/js/owl.carousel.min.js')}}"></script>
<script src="{{asset('theme/js/jquery.magnific-popup.min.js')}}"></script>
<script src="{{asset('theme/js/aos.js')}}"></script>
<script src="{{asset('theme/js/jquery.animateNumber.min.js')}}"></script>
<script src="{{asset('theme/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('theme/js/scrollax.min.js')}}"></script>
<script src="{{asset('theme/js/main.js')}}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
@yield('scripts')
@section('embed_code_body')
    @foreach($footer_embed_codes as $c)
        {{$c}}
    @endforeach
@show
</body>
</html>