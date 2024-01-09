@extends('theme.components.layout')

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
                            <li> <a href="/" title="Back to the home page">Trang chủ</a></li>
                            <li> <span>Giỏ hàng</span></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <section class="cart-page container">
        <div class="col-lg-12 mb-30">
            <div class="cart-table-container">
                @if (\App\Utils\CartUtil::getInstance()->getProductCount() == 0)
                    <div class="empty_cart" style="text-align: center">
                        <h2 class="h4 summary-title">Bạn chưa thêm sản phẩm nào vào giỏ hàng</h2>
                        {{-- <a href="/products" class="as-btn">Tiếp tục mua sắm</a> --}}
                    </div>
                @endif
                @if (\App\Utils\CartUtil::getInstance()->getProductCount() != 0)
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th class="pro-title product-name" colspan="2">Sản phẩm</th>
                                <th class="pro-price product-price">Giá</th>
                                <th class="pro-quantity product-quantity">Số lượng</th>
                                <th class="pro-subtotal product-subtotal">Thành tiền</th>
                                <th class="pro-remove product-remov"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cart->getItems() as $productId => $item)
                                <tr>
                                    <td class="product-thumbnail pro-thumbnail">
                                        <img id="bannerImage-01" class="lazyautosizes lazyloaded"
                                            src="{{ $item['item']['image'] }}"
                                            onclick="redirectDetail('/product_categories/{{ $item['item']['category_slug'] }}/products/{{ $item['item']['slug'] }}')">
                                    </td>
                                    <td class="product-name pro-name">
                                        <a
                                            href="/product_categories/{{ $item['item']['category_slug'] }}/products/{{ $item['item']['slug'] }}">
                                            @if ($item['size'] == '0')
                                                {{ $item['item']['name'] }}
                                            @endif
                                            @if ($item['size'] != '0')
                                                {{ $item['item']['name'] }}<br>
                                                <p style="color:#808080">size
                                                    {{ \App\Utils\Caches\VariantUtil::getInstance()->getNameVariantById($item['size']) }}
                                                </p>
                                            @endif
                                        </a>
                                    </td>
                                    <td class="product-price pro-price">
                                        <span class="price amount">
                                            <span
                                                class="money">{{ number_format($item['unit_price'], 0, ',', '.') }}đ</span>
                                        </span>
                                    </td>
                                    <td class="product-quantity pro-quantity">
                                        <form class="quantity" action="/cart/updateQuantity/{{ $productId }}">
                                            <div class="control">
                                                <a href="/cart/minusQuantity/{{ $productId }}" class="bttn bttn-left"
                                                    id="minus">
                                                    <span>-</span>
                                                </a>
                                                <input type="number" class="input" id="quantity"
                                                    value="{{ $item['quantity'] }}">
                                                <a href="/cart/pushQuantity/{{ $productId }}" class="bttn bttn-right"
                                                    id="plus">
                                                    <span>+</span>
                                                </a>
                                            </div>
                                            @csrf
                                        </form>
                                    </td>
                                    <td class="total-price pro-subtotal"><span class="price"><span
                                                class="money">{{ number_format($item['amount'], 0, ',', '.') }}đ</span></span>
                                    </td>
                                    <td class="product-remove pro-remove">
                                        <a href="/cart/removeItem/{{ $productId }}">
                                            <i class="fal fa-times"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="tr-cart-mb">
                                <td colspan="4" class="total_amount_text" style="text-align: right">
                                    <h3 class="h5 summary-title total_amount_text_title" style="padding-right:20px">Tổng
                                        Giá Trị</h3>
                                </td>
                                <td colspan="2" data-title="Tổng giá trị">
                                    <h5 class="total_amount">
                                        <bdi>{{ number_format($cart->getTotalAmount(), 0, ',', '.') }}đ<span>
                                            </span></bdi>
                                    </h5>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endif
            </div>

            {{-- <div class="cart-btn-list">
            <div class="row align-items-center">
                <div class="megabtn-cart col-lg-12 text-end">
                    <a href="/products" class="btn-custom">tiếp tục mua sắm</a>
                    <a href="/checkout" class="btn-custom">Mua hàng</a>
                </div>
            </div>
        </div> --}}
            @if (\App\Utils\CartUtil::getInstance()->getProductCount() == 0)
                <div class="cart-btn-list">
                    <div class="row align-items-center">
                        <div class="megabtn-cart col-lg-12 text-end">
                            <a href="/products" class="btn-custom">tiếp tục mua sắm</a>

                        </div>
                    </div>
                </div>
            @endif
            @if (\App\Utils\CartUtil::getInstance()->getProductCount() != 0)
                <div class="cart-btn-list">
                    <div class="row align-items-center">
                        <div class="megabtn-cart col-lg-12 text-end">
                            <a href="/products" class="btn-custom">tiếp tục mua sắm</a>
                            <a href="/checkout" class="btn-custom">Mua hàng</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    <section class="cart-end-part">
        <h4 class="cart-end-title">Đề xuất cho bạn</h4>
        <div id="saleSlider" class="carousel slide" data-bs-ride="carousel">

            <!-- The slideshow/carousel -->
            <div class="carousel-inner">
                <div class="sale-slide carousel-item active">
                    <section class="section-products">
                        <div class="container">
                            <div class="row">
                                <!-- Single Product -->
                                @for ($i = 0; $i < 4; $i++)
                                    @if (isset($products[$i]))
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div id="{{ $products[$i]->slug }}" class="single-product">
                                                <div class="part-1" >
                                                    <div class="product-img-hd" onclick="redirectDetail('/product_categories/{{ $products[$i]->category_slug }}/products/{{ $products[$i]->slug }}')"><img class="lazy" src="{{\App\Utils\ImageUtil::getMinUrl($products[$i]->image)}}"
                                                     data-src="{{$products[$i]->image}}" ></div>

                                                    @foreach ($products[$i]->tags as $products[$i]->tag)
                                                        @if ($products[$i]->tag->summary == 'Sale' || $products[$i]->tag->summary == 'Mới' || $products[$i]->tag->summary == 'Hết hàng')
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
                                                        <li><a href="javascript:;" onclick="redirectDetail('/product_categories/{{ $products[$i]->category_slug }}/products/{{ $products[$i]->slug }}')"><i class="far fa-shopping-bag"></i></a></li>
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
                                                        <h4 class="product-price">{{ number_format($products[$i]->price) }}đ</h4>
                                                    @endif
                                                    @if ($products[$i]->sale_price != null)
                                                        <h4 class="product-price">{{ number_format($products[$i]->sale_price) }}đ
                                                        </h4>
                                                        <h4 class="product-old-price">{{ number_format($products[$i]->price) }}đ
                                                        </h4>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endfor

                            </div>
                        </div>
                    </section>
                </div>
                <div class="sale-slide carousel-item">
                    <section class="section-products">
                        <div class="container">
                            <div class="row">
                                @for ($i = 3; $i < 7; $i++)
                                    @if (isset($products[$i]))
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div id="{{ $products[$i]->slug }}" class="single-product">
                                                <div class="part-1" >
                                                    <div class="product-img-hd" onclick="redirectDetail('/product_categories/{{ $products[$i]->category_slug }}/products/{{ $products[$i]->slug }}')"><img class="lazy" src="{{\App\Utils\ImageUtil::getMinUrl($products[$i]->image)}}"
                                                     data-src="{{$products[$i]->image}}" ></div>

                                                    @foreach ($products[$i]->tags as $products[$i]->tag)
                                                        @if ($products[$i]->tag->summary == 'Sale' || $products[$i]->tag->summary == 'Mới' || $products[$i]->tag->summary == 'Hết hàng')
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
                                                        <li><a href="javascript:;" onclick="redirectDetail('/product_categories/{{ $products[$i]->category_slug }}/products/{{ $products[$i]->slug }}')"><i class="far fa-shopping-bag"></i></a></li>
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
                                                        <h4 class="product-price">{{ number_format($products[$i]->price) }}đ</h4>
                                                    @endif
                                                    @if ($products[$i]->sale_price != null)
                                                        <h4 class="product-price">{{ number_format($products[$i]->sale_price) }}đ
                                                        </h4>
                                                        <h4 class="product-old-price">{{ number_format($products[$i]->price) }}đ
                                                        </h4>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            <!-- Phân trang slider -->
            <div class="sale-indicators carousel-indicators">
                <button type="button" data-bs-target="#saleSlider" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#saleSlider" data-bs-slide-to="1"></button>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('theme/App/js/cart.js') }}" defer></script>
@endsection
