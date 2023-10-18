@extends('theme.components.layout')
@section('styles')
    <link rel="stylesheet" media="print" onload="this.onload=null;this.removeAttribute('media');"
          href="{{ asset('theme/App/css/home.css') }}">
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
    @include('theme.components.banner')
    <!-- Product Tabs -->
    <section class="mp">
        <div class="tab">
            @foreach ($tags as $key => $tag)
                @if ($tag->slug == 'tat-ca')
                    <button class="tablinks" onclick="openProduct(event, '{{ $tag->slug }}')"
                            id="defaultOpen">{{ $tag->name }}</button>
                    <span class="tab-separator">/</span>
                @endif
                @if ($tag->slug == 'san-pham-ban-chay')
                    <button class="tablinks" onclick="openProduct(event, '{{ $tag->slug }}')"
                            id="defaultOpen">{{ $tag->name }}</button>
                    <span class="tab-separator">/</span>
                @endif
                @if ($tag->slug == 'san-pham-moi')
                    <button class="tablinks" onclick="openProduct(event, '{{ $tag->slug }}')"
                            id="defaultOpen">{{ $tag->name }}</button>
                @endif
            @endforeach
        </div>
        @foreach ($tagsFilters as $key => $tagsFilter)
            <div id="{{ $tagsFilter->slug }}" class="tabcontent">
                <section class="section-products">
                    <div class="container">
                        <div class="row">
                            <!-- Single Product -->
                            @php
                                if ($tagsFilter->slug == 'tat-ca') {
                                    $productstagFilter = $tagsFilter->products->take(8);
                                } else {
                                    $productstagFilter = $tagsFilter->products->take(4);
                                }
                            @endphp
                            @foreach ($productstagFilter as $key => $product)
                                @if ($product->published == 1)
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div id="{{ $product->slug }}" class="single-product">
                                            <div class="part-1">
                                                <div class="product-img-hd"
                                                     onclick="redirectDetail('/product_categories/{{ $product->category_slug }}/products/{{ $product->slug }}')">
                                                    <img class="lazy"
                                                         src="{{ \App\Utils\ImageUtil::getMinUrl($product->image) }}"
                                                         data-src="{{ $product->image }}">
                                                </div>

                                                @foreach ($product->tags as $product->tag)
                                                    @if ($product->tag->summary == 'Sale' || $product->tag->summary == 'Mới' || $product->tag->summary == 'Hết hàng')
                                                        @if ($product->tag->summary == 'Sale')
                                                            <span
                                                                    class="sale discount">{{ explode(' ', $product->tag->name)[1] }}</span>
                                                            <span
                                                                    class="discount">{{ explode(' ', $product->tag->name)[0] }}</span>
                                                        @endif
                                                        @if ($product->tag->summary != 'Sale')
                                                            <span class="new">{{ $product->tag->name }}</span>
                                                        @endif
                                                    @endif
                                                @endforeach
                                                <ul>
                                                    <li><a href="javascript:;"><i class="far fa-heart"></i></a></li>
                                                    <li><a href="javascript:;"
                                                           onclick="redirectDetail('/product_categories/{{ $product->category_slug }}/products/{{ $product->slug }}')"><i
                                                                    class="far fa-shopping-bag"></i></a></li>
                                                </ul>
                                                <ul class="hover-size">
                                                    @foreach (\App\Utils\Caches\VariantUtil::getInstance()->getVariant($product->id) as $item)
                                                        <li><a href="javascript:;">
                                                                <p>{{ $item->name }}</p>
                                                            </a></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="part-2">
                                                <h3 class="product-title"><a
                                                            href="/product_categories/{{ $product->category_slug }}/products/{{ $product->slug }}">{{ $product->name }}</a>
                                                </h3>

                                                @if ($product->sale_price == null)
                                                    <h4 class="product-price">{{ number_format($product->price) }}đ</h4>
                                                @else
                                                    <h4 class="product-price">{{ number_format($product->sale_price) }}đ
                                                    </h4>
                                                    <h4 class="product-old-price">{{ number_format($product->price) }}đ
                                                    </h4>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </section>
                <div class="os">
                    <a href="/products">
                        <button class="btn-custom">
                            <span>+</span>
                            <span>mua sắm trực tuyến</span>
                        </button>
                    </a>
                </div>
            </div>
        @endforeach

    </section>
    <!-- End Product Tabs -->

    <!-- Chính sách -->
    <section class="container">
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
    </section>
    <!-- End chính sách -->

    <!-- Flash Sale -->
    <div class="flash-sale">
        <section class="container">
            <div class="fs d-flex justify-content-between">
                {{-- @foreach ($banners['Banner flash sale'] as $item)
                    <div class="fs-banner">
                        <img class="lazy" data-src="{{ $item['image'] }}">
                    </div>
                @endforeach --}}
                @if (isset($flash_sale))
                    <div class="fs-content">
                        <h3>{{ $flash_sale['name'] }}</h3>
                        <input type="text" id="expired_date" value="{{ $flash_sale['expired_date'] }}" hidden>
                        <div class="fs-countdown">
                            <span class="countdown-index">
                                <span id="fs_day" class="fs-number"></span>
                                <span class="fs-time-text">ngày</span>
                            </span>
                            <span class="fs-space">:</span>
                            <span class="countdown-index">
                                <span id="fs_hour" class="fs-number"></span>
                                <span class="fs-time-text">giờ</span>
                            </span>
                            <span class="fs-space">:</span>
                            <span class="countdown-index">
                                <span id="fs_minute" class="fs-number"></span>
                                <span class="fs-time-text">phút</span>
                            </span>
                            <span class="fs-space">:</span>
                            <span class="countdown-index">
                                <span id="fs_second" class="fs-number"></span>
                                <span class="fs-time-text">giây</span>
                            </span>
                        </div>
                        <form action="/promotion_products">
                            <a href="javascript:;">
                                <input type="number" name="promotion_id" value="{{ $flash_sale['id'] }}" hidden>
                                <button class="btn-custom" id="btn-promotion" type="submit">
                                    <i class="fas fa-shopping-bag"></i>
                                        Xem chi tiết
                                </button>
                            </a>
                        </form>
                    </div>
                @endif
            </div>
        </section>
    </div>


    <!-- End Flash Sale -->

    <!-- Sale -->
    <div class="sale">
        <section class="container">
            <div class="salebox d-flex justify-content-between">
                @foreach ($tagsFilters as $tagsFilter)
                    @if ($tagsFilter->slug == 'san-pham-khuyen-mai')
                        <div class="sale-x">
                            <div class="sale-product">
                                <div class="sale-column">
                                    <h3 class="sale-title">{{ $tagsFilter->name }}</h3>
                                    @foreach ($tagsFilter->products->take(3) as $product)
                                        @if ($product->published == 1)
                                            <div class="sale-product-box d-flex justify-content-start">
                                                <img onclick="redirectDetail('/product_categories/{{ $product->category_slug }}/products/{{ $product->slug }}')"
                                                     class="lazy" data-src="{{ $product->image }}">
                                                <div>
                                                    <h4 class="sale-product-name"><a
                                                                href="/product_categories/{{ $product->category_slug }}/products/{{ $product->slug }}">{{ $product->name }}</a>
                                                    </h4>
                                                    @if ($product->sale_price ==null)
                                                        <div class="sale-price">
                                                            <span
                                                                    class="sale-product-price">{{ number_format($product->price) }}
                                                                đ</span>
                                                        </div>
                                                    @else
                                                        <div class="sale-price">
                                                            <span
                                                                    class="product-old-price">{{ number_format($product->price) }}
                                                                đ</span>
                                                            <span
                                                                    class="sale-product-price">{{ number_format($product->sale_price) }}
                                                                đ</span>
                                                        </div>
                                                    @endif
                                                    <div class="rating">
                                                        <span class="star-rating">
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($tagsFilter->slug == 'goi-y')
                        <div class="sale-x">
                            <div class="sale-product">
                                <div class="sale-column">
                                    <h3 class="sale-title">{{ $tagsFilter->name }}</h3>
                                    @foreach ($tagsFilter->products->take(3) as $product)
                                        @if ($product->published == 1)
                                            <div class="sale-product-box d-flex justify-content-start">
                                                <img onclick="redirectDetail('/product_categories/{{ $product->category_slug }}/products/{{ $product->slug }}')"
                                                     class="lazy" data-src="{{ $product->image }}">
                                                <div>
                                                    <h4 class="sale-product-name"><a
                                                                href="/product_categories/{{ $product->category_slug }}/products/{{ $product->slug }}">{{ $product->name }}</a>
                                                    </h4>
                                                    @if ($product->sale_price == null)
                                                        <div class="sale-price">
                                                            <span
                                                                    class="sale-product-price">{{ number_format($product->price) }}
                                                                đ</span>
                                                        </div>
                                                    @else
                                                        <div class="sale-price">
                                                            <span
                                                                    class="product-old-price">{{ number_format($product->price) }}
                                                                đ</span>
                                                            <span
                                                                    class="sale-product-price">{{ number_format($product->sale_price) }}
                                                                đ</span>
                                                        </div>
                                                    @endif
                                                    <div class="rating">
                                                        <span class="star-rating">
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
                {{-- @foreach ($banners['Banner father day sale'] as $item)
                    <div class="sale-banner">
                        <img class="lazy" data-src="{{ $item['image'] }}">
                    </div>
                @endforeach --}}
            </div>
        </section>
    </div>

    <!-- Carousel -->
    <div id="saleSlider" class="carousel slide" data-bs-ride="carousel">

        <!-- The slideshow/carousel -->
        <div class="carousel-inner">
            <div class="sale-slide carousel-item active">
                <section class="section-products">
                    <div class="container">
                        <div class="row">
                            <!-- Single Product -->
                            @for ($i = 0; $i < 4; $i++)
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <div id="{{ $products[$i]->slug }}" class="single-product">
                                        <div class="part-1">
                                            <div class="product-img-hd"
                                                 onclick="redirectDetail('/product_categories/{{ $products[$i]->category_slug }}/products/{{ $products[$i]->slug }}')">
                                                <img class="lazy"
                                                     src="{{ \App\Utils\ImageUtil::getMinUrl($products[$i]->image) }}"
                                                     data-src="{{ $products[$i]->image }}">
                                            </div>

                                            @foreach ($products[$i]->tags as $products[$i]->tag)
                                                @if (
                                                    $products[$i]->tag->summary == 'Sale' ||
                                                        $products[$i]->tag->summary == 'Mới' ||
                                                        $products[$i]->tag->summary == 'Hết hàng')
                                                    @if ($products[$i]->tag->summary == 'Sale')
                                                        <span
                                                                class="sale discount">{{ explode(' ', $products[$i]->tag->name)[1] }}</span>
                                                        <span
                                                                class="discount">{{ explode(' ', $products[$i]->tag->name)[0] }}</span>
                                                    @endif
                                                    @if ($products[$i]->tag->summary != 'Sale')
                                                        <span class="new">{{ $products[$i]->tag->name }}</span>
                                                    @endif
                                                @endif
                                            @endforeach
                                            <ul>
                                                <li><a href="javascript:;"><i class="far fa-heart"></i></a></li>
                                                <li><a href="javascript:;"
                                                       onclick="redirectDetail('/product_categories/{{ $products[$i]->category_slug }}/products/{{ $products[$i]->slug }}')"><i
                                                                class="far fa-shopping-bag"></i></a>
                                                </li>
                                            </ul>
                                            <ul class="hover-size">
                                                @foreach (\App\Utils\Caches\VariantUtil::getInstance()->getVariant($products[$i]->id) as $item)
                                                    <li><a href="javascript:;">
                                                            <p>{{ $item->name }}</p>
                                                        </a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="part-2">

                                            <h3 class="product-title"><a
                                                        href="/product_categories/{{ $products[$i]->category_slug }}/products/{{ $products[$i]->slug }}">{{ $products[$i]->name }}</a>
                                            </h3>

                                            @if ($products[$i]->sale_price == null)
                                                <h4 class="product-price">
                                                    {{ number_format($products[$i]->price) }}đ
                                                </h4>
                                            @else
                                                <h4 class="product-price">
                                                    {{ number_format($products[$i]->sale_price) }}đ
                                                </h4>
                                                <h4 class="product-old-price">
                                                    {{ number_format($products[$i]->price) }}đ
                                                </h4>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </section>
            </div>
            {{-- <div class="sale-slide carousel-item">
                <section class="section-products">
                    <div class="container">
                        <div class="row">
                            @for ($i = 4; $i < 8; $i++)
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <div id="{{ $products[$i]->slug }}" class="single-product">
                                        <div class="part-1">
                                            <div class="product-img-hd"
                                                 onclick="redirectDetail('/product_categories/{{ $products[$i]->category_slug }}/products/{{ $products[$i]->slug }}')">
                                                <img class="lazy"
                                                     src="{{ \App\Utils\ImageUtil::getMinUrl($products[$i]->image) }}"
                                                     data-src="{{ $products[$i]->image }}">
                                            </div>

                                            @foreach ($products[$i]->tags as $products[$i]->tag)
                                                @if (
                                                    $products[$i]->tag->summary == 'Sale' ||
                                                        $products[$i]->tag->summary == 'Mới' ||
                                                        $products[$i]->tag->summary == 'Hết hàng')
                                                    @if ($products[$i]->tag->summary == 'Sale')
                                                        <span
                                                                class="sale discount">{{ explode(' ', $products[$i]->tag->name)[1] }}</span>
                                                        <span
                                                                class="discount">{{ explode(' ', $products[$i]->tag->name)[0] }}</span>
                                                    @endif
                                                    @if ($products[$i]->tag->summary != 'Sale')
                                                        <span class="new">{{ $products[$i]->tag->name }}</span>
                                                    @endif
                                                @endif
                                            @endforeach
                                            <ul>
                                                <li><a href="javascript:;"><i class="far fa-heart"></i></a></li>
                                                <li><a href="javascript:;"
                                                       onclick="redirectDetail('/product_categories/{{ $products[$i]->category_slug }}/products/{{ $products[$i]->slug }}')"><i
                                                                class="far fa-shopping-bag"></i></a>
                                                </li>
                                            </ul>
                                            <ul class="hover-size">
                                                @foreach (\App\Utils\Caches\VariantUtil::getInstance()->getVariant($products[$i]->id) as $item)
                                                    <li><a href="javascript:;">
                                                            <p>{{ $item->name }}</p>
                                                        </a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="part-2">

                                            <h3 class="product-title"><a
                                                        href="/product_categories/{{ $products[$i]->category_slug }}/products/{{ $products[$i]->slug }}">{{ $products[$i]->name }}</a>
                                            </h3>

                                            @if ($products[$i]->sale_price == null)
                                                <h4 class="product-price">
                                                    {{ number_format($products[$i]->price) }}đ
                                                </h4>
                                            @else
                                                <h4 class="product-price">
                                                    {{ number_format($products[$i]->sale_price) }}đ
                                                </h4>
                                                <h4 class="product-old-price">
                                                    {{ number_format($products[$i]->price) }}đ
                                                </h4>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </section>
            </div> --}}
        </div>
        <!-- Phân trang slider -->
        <div class="sale-indicators carousel-indicators">
            <button type="button" data-bs-target="#saleSlider" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#saleSlider" data-bs-slide-to="1"></button>
        </div>
    </div>
    <!-- End Sale -->

    <!-- Phản hồi -->
    <div class="home-testimonial">
        <section class="box-testimonial">
            <div id="testimonial" class="carousel slide" data-bs-ride="carousel">

                <!-- The slideshow/carousel -->
                <div class="carousel-inner">
                    @foreach ($comments as $key => $comment)
                        @if ($key == 0)
                            <div class="carousel-item active">
                                <div class="ph d-flex justify-content-between">
                                    <img class="lazy" data-src="{{ $comment['customer_avatar'] }}">
                                    <div class="ph-content">
                                        <img class="lazy"
                                             data-src="{{ asset('theme/App/img/testimonial/quote-icon.webp') }}">
                                        <div class="text mb-40"> {{ $comment['customer_comment'] }}</div>
                                        <div class="client-info">
                                            <p class="name">{{ $comment['customer_name'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($key != 0)
                            <div class="carousel-item">
                                <div class="ph d-flex justify-content-between">
                                    <img class="lazy" data-src="{{ $comment['customer_avatar'] }}">
                                    <div class="ph-content">
                                        <img class="lazy"
                                             data-src="{{ asset('theme/App/img/testimonial/quote-icon.webp') }}">
                                        <div class="text mb-40"> {{ $comment['customer_comment'] }}</div>
                                        <div class="client-info">
                                            <p class="name">{{ $comment['customer_name'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <!-- Left and right controls/icons -->
                <button class="carousel-control-prev" type="button" data-bs-target="#testimonial" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#testimonial" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </section>
    </div>
    <!-- End Phản Hồi -->

    <div class="blog">
        <section class="container">
            <div class="blog-row d-flex justify-content-between">
                <div class="blog-intro">
                    <h2>{{ $BlogCategory['name'] }}</h2>
                    <p>{!! $BlogCategory['summary'] !!}</p>
                    <a type="button" href="/blog" class="btn-custom">xem tất cả</a>
                </div>
                <div id="blogBlock" class="carousel slide" data-bs-ride="carousel">
                    <!-- The slideshow/carousel -->
                    <div class="blogslide carousel-inner">
                        <div class="carousel-item active">
                            <div class="bl d-flex justify-content-between">
                                @for ($i = 0; $i < 2; $i++)
                                    <div class="home-each-blog">
                                        <img class="lazy" data-src="{{ $posts[$i]->image }}"
                                             onclick="redirectDetail('post_categories/{{ $posts[$i]->category_slug }}/posts/{{ $posts[$i]->slug }}')">
                                        <div class="blog-date">
                                            <span><i class="far fa-calendar-alt"></i></span>
                                            <span
                                                    class="the-date">{{ date('d/m/Y', strtotime($posts[$i]->created_at)) }}</span>
                                        </div>
                                        <a
                                                href="post_categories/{{ $posts[$i]->category_slug }}/posts/{{ $posts[$i]->slug }}">
                                            <h2 class="post-title">{{ $posts[$i]->name }}</h2>
                                        </a>
                                        <div class="post-excerpt">{!! $posts[$i]->summary !!}</div>
                                        <a class="blog-readmore-btn"
                                           href="/post_categories/{{ $posts[$i]->category_slug }}/posts/{{ $posts[$i]->slug }}">Xem
                                            Thêm</a>
                                    </div>
                                @endfor
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="bl d-flex justify-content-between">
                                @for ($i = 2; $i < 4; $i++)
                                    <div class="home-each-blog">
                                        <img class="lazy" data-src="{{ $posts[$i]->image }}"
                                             onclick="redirectDetail('post_categories/{{ $posts[$i]->category_slug }}/posts/{{ $posts[$i]->slug }}')">
                                        <div class="blog-date">
                                            <span><i class="far fa-calendar-alt"></i></span>
                                            <span
                                                    class="the-date">{{ date('d/m/Y', strtotime($posts[$i]->created_at)) }}</span>
                                        </div>
                                        <a
                                                href="post_categories/{{ $posts[$i]->category_slug }}/posts/{{ $posts[$i]->slug }}">
                                            <h2 class="post-title">{{ $posts[$i]->name }}</h2>
                                        </a>
                                        <div class="post-excerpt">{!! $posts[$i]->summary !!}</div>
                                        <a class="blog-readmore-btn"
                                           href="/post_categories/{{ $posts[$i]->category_slug }}/posts/{{ $posts[$i]->slug }}">Xem
                                            Thêm</a>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <!-- Left and right controls/icons -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#blogBlock"
                            data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#blogBlock"
                            data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            </div>
        </section>
    </div>

    <!-- Follow fanpage facebook -->
    <div class="fanpage-section">
        <section class="container">
            <div class="section-title section-title--one text-center">
                <h2>
                    <a href="javascript:;">@Lebro_Store</a>
                </h2>
                <p>Liên hệ ngay chúng tôi để cập nhật thêm nhiều thông tin hữu ích</p>
            </div>
            <div class="owl-carousel owl-theme">
                @foreach ($products as $product)
                    <div class="item">
                        <h4>
                            <a href="/product_categories/{{ $product->category_slug }}/products/{{ $product->slug }}"><img
                                        class="img-fanpage lazy"
                                        src="{{ \App\Utils\ImageUtil::getMinUrl($product->image) }}"
                                        data-src="{{ $product->image }}"/></a></h4>
                    </div>
                @endforeach
            </div>
            <!-- custom JS code after importing jquery and owl -->

        </section>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('theme/App/js/home.js') }}"></script>
@endsection
