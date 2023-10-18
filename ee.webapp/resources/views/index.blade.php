@extends('master')
@section('main')
    <div class="container">
        @include('block/header')
        @include('block/featured-post')
        @include('block/recent-post')
        @include('block/footer')
    </div>
@endsection
