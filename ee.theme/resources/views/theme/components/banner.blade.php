<div id="homeSlider" class="carousel carousel-dark slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active" data-bs-interval="10000">
            <img src="{{$banners['Banner đầu trang'][0]['image']}}" class="d-block w-100" alt="Lebro">
            <div class="slider-title1 d-md-block">
                <p>{{$banners['Banner đầu trang'][0]['summary']}}</p>
                <h2>{{ $banners['Banner đầu trang'][0]['name']}}</h2>
                <a type="button" href="/products" class="btn-custom">mua ngay</a>
            </div>
        </div>
       @foreach ($banners['Banner đầu trang'] as $key=>$item)
        @if($key>0)
            <div class="carousel-item" data-bs-interval="10000">
                <img src="{{$item['image']}}" class="d-block w-100" alt="{{$item['alt']}}">
                <div class=" slider-title2 d-md-block">
                    <p>{{$item['summary']}}</p>
                    <h2>{{$item['name']}}</h2>
                    <a type="button" href="/products" class="btn-custom">mua ngay</a>
                </div>
            </div>
        @endif
       @endforeach
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#homeSlider" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#homeSlider" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
    </button>
</div>

<!-- End Slider -->
