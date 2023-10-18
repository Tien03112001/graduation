@extends('theme.components.layout')
@section('content')
    <section>
        <div class="container">
            <div class="feature-inner">
                <h2 class="section-title text-center">Lỗi hệ thống</h2>
                <div class="row">
                    <div class="col-12">
                        <p class="text-center">{{$message??'Có lỗi xảy ra'}}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection