@extends('theme.components.layout')
@section('styles')
    <link rel="stylesheet" media="print" onload="this.onload=null;this.removeAttribute('media');"
          href="{{ asset('theme/App/css/about.css') }}">
@endsection

@section('content')
    @if (session('msg_success'))
        <div>
            <div class="alert alert-success" id="alert" onclick="this.parentElement.style.display='none';" role="alert">
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
    <div class="breadcrumb-area overlay-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-left ">
                    <nav class="" role="navigation" aria-label="breadcrumbs">
                        <ul class="breadcrumb-list">
                            <li><a href="/" title="Back to the home page">Trang chủ</a></li>
                            <li><span>{{$page->name}}</span></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- End breadcrumb -->

    <!-- About us page -->
    <div class="about-us-page">
        <section class="container">
            @if ($post != null)
                {!!$post->article->content!!}
            @endif
        </section>

        <section class="about-policy">
            <div class="container">
                <div class="policybox d-flex justify-content-between">
                    @foreach ($policies as $policiy)
                        <div class="policy">
                            <h4>{{ $policiy['name'] }}</h4>
                            @if ($policiy['icon'] == 'null')
                                <p>{{ $policiy['content'] }}</p>
                            @endif
                            @if ($policiy['icon'] != 'null')
                                <img class="lazy" data-src="{{ $policiy['icon'] }}">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="about-review">
            <div class="container">
                <div class="about-title text-center">
                    <h2>Khách hàng đã đánh giá như nào về Lebro?</h2>
                    <p>Hãy cùng xem một vài đánh giá của khách hàng nhé</p>
                </div>
            </div>

            <div id="aboutSlider" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="about-slide carousel-item active">
                        <div class="lebro-about-rv">
                            <div class="about-review-block">
                                @for ($i = 0; $i < 2; $i++)
                                    @if (isset($comments[$i]))
                                        <div class="about-cs-rv-bl">
                                            <p class="about-rv-content">
                                                {{ $comments[$i]['customer_comment'] }}
                                            </p>
                                            <div class="about-rate">
                                                <i class="fas fa-star rate"></i>
                                                <i class="fas fa-star rate"></i>
                                                <i class="fas fa-star rate"></i>
                                                <i class="fas fa-star rate"></i>
                                                <i class="fas fa-star rate"></i>
                                            </div>
                                            <div class="about-user">
                                                <img class="lazy"
                                                     data-src="https://cdn-icons-png.flaticon.com/512/1077/1077114.png">
                                                <p class="about-user-name">{{ $comments[$i]['customer_name'] }}</p>
                                            </div>
                                        </div>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="about-slide carousel-item">
                        <div class="lebro-about-rv">
                            <div class="about-review-block">
                                @for ($i = 2; $i < 4; $i++)
                                    @if (isset($comments[$i]))
                                        <div class="about-cs-rv-bl">
                                            <p class="about-rv-content">
                                                {{ $comments[$i]['customer_comment'] }}
                                            </p>
                                            <div class="about-rate">
                                                <i class="fas fa-star rate"></i>
                                                <i class="fas fa-star rate"></i>
                                                <i class="fas fa-star rate"></i>
                                                <i class="fas fa-star rate"></i>
                                                <i class="fas fa-star rate"></i>
                                            </div>
                                            <div class="about-user">
                                                <img class="lazy"
                                                     data-src="https://cdn-icons-png.flaticon.com/512/1077/1077114.png">
                                                <p class="about-user-name">{{ $comments[$i]['customer_name'] }}</p>
                                            </div>
                                        </div>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#aboutSlider"
                            data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#aboutSlider"
                            data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </section>
    </div>


@endsection

@section('scripts')

@endsection
