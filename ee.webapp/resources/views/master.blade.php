<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="{{$setting['favicon'] ?? ''}}">
    <title>{{$meta['title']??$settings['meta_title']??'Giga Web'}}</title>
    <meta name="title"
          content="{{$meta['title']??$settings['meta_title']??'Giga Web'}}">
    <meta name="description"
          content="{{$meta['description']??$settings['meta_description']??'Giga Web'}}">
    @if(isset($meta['keywords']))
        <meta name="keywords" content="{{$meta['keywords']}}">
    @endif
    <meta property="og:url" content="{{url()->current()}}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title"
          content="{{$meta['title']??$settings['meta_title']??'Giga Web'}}"/>
    <meta property="og:description"
          content="{{$meta['description']??$settings['meta_description']??'Giga Web'}}"/>
    <meta property="og:image"
          content="{{$meta['image']??$settings['meta_image']??'/default_meta_image.jpg'}}"/>
    @if(isset($meta['canonical']))
        <link rel="canonical" href="{{$meta['canonical']}}"/>
    @else
        <link rel="canonical" href="{{url()->current()}}"/>
@endif
    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <!-- Fonts -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Righteous" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="/css/mediumish.css" rel="stylesheet">
</head>
<body>
<!-- Begin Nav
================================================== -->
<nav class="navbar navbar-toggleable-md navbar-light bg-white fixed-top mediumnavigation">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="container">
        <!-- Begin Logo -->
        <a class="navbar-brand" href="/">
            <img src="{{$settings['favicon']}}" alt="logo">
        </a>
        <!-- End Logo -->
        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            @include('block/menu')
            <!-- Begin Search -->
            <form class="form-inline my-2 my-lg-0" action="/tim-kiem" method="get">
                <input class="form-control mr-sm-2" type="text" placeholder="Search" name="keyword">
                <button type="submit" style="background: none;border: none;margin-left: -45px;display: inline-block;margin-top: 3px;cursor: pointer;">
                    <svg class="svgIcon-use" width="25" height="25" viewbox="0 0 25 25"><path d="M20.067 18.933l-4.157-4.157a6 6 0 1 0-.884.884l4.157 4.157a.624.624 0 1 0 .884-.884zM6.5 11c0-2.62 2.13-4.75 4.75-4.75S16 8.38 16 11s-2.13 4.75-4.75 4.75S6.5 13.62 6.5 11z"></path></svg>
                </button>
            </form>
            <!-- End Search -->
        </div>
    </div>
</nav>
<!-- End Nav
================================================== -->

<!-- Begin Site Title
================================================== -->
{{--<div class="container">--}}
    @yield('main')
{{--</div>--}}
<!-- /.container -->

<!-- Bootstrap core JavaScript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="js/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
