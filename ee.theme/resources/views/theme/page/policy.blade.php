@extends('theme.components.layout')

@section('styles')
    <style>
        ul {
            padding-left: 30px
        }

        .post-bigblock.col-lg-12.col-md-12.col-sm-6.col-12 {
            padding-left: 100px;
            padding-right: 100px;
        }

        @media only screen and (max-width: 575px) {
            .post-bigblock.col-lg-12.col-md-12.col-sm-6.col-12 {
                padding-left: 40px;
                padding-right: 40px;
            }
        }
    </style>
@endsection

@section('content')
    @if (session('msg_success'))
        <div>
            <div class="alert alert-success" id="alert" onclick="this.parentElement.style.display='none';"
                 role="alert">
                {{ session('msg_success') }}
            </div>
        </div>
    @endif
    @if (session('msg_error'))
        <div>
            <div class="alert alert-danger" id="alert" onclick="this.parentElement.style.display='none';" role="alert">
                {{ session('msg_error') }}
            </div>
        </div>
    @endif

    <!-- Blog page -->
    <div class="blog-page-wrapper mb-100 mb-sm-50 mb-md-60 mt-100 mt-xs-80">
        <div class=" container ">
            <div class="row ">
                <div class="order-1 order-lg-2 mb-md-70 mb-sm-70">
                    <div class="row ">
                        <div class="post-bigblock col-lg-12 col-md-12 col-sm-6 col-12">
                            <div class="single-slider-post detail-post">
                                @if (isset($post))
                                    {{-- <div class="single-slider-post__image">
                                        <a href="javascript:;">
                                            <img id="" class="lazyautosizes lazyloaded" src="{{ $post->image }}">
                                        </a>
                                    </div> --}}
                                    <div class="single-slider-post__content">
                                        <div class="post-info d-flex flex-wrap align-items-center mb-10">
                                            {{-- <h2 class="post-title">
                                                <a>{{ $post->name }}</a>
                                            </h2> --}}
                                            {{-- <div class="post-info d-flex flex-wrap align-items-center">
                                                <div class="post-user pr-30"><i class="far fa-user-alt"></i>
                                                    <p>Admin</p>
                                                </div>
                                                <div class="post-date mb-0 pr-30"><i class="far fa-calendar-alt"></i>
                                                    <p>{{ date('d/m/Y', strtotime($post->created_at)) }}</p>
                                                </div>
                                            </div> --}}

                                            <div class="single-blog-post-section">
                                                @if (isset($post->article->content))
                                                    {!! $post->article->content !!}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End blog page -->
@endsection

@section('scripts')
@endsection
