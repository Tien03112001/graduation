@extends('theme.components.layout')

@section('styles')
    <link rel="stylesheet" media="print" onload="this.onload=null;this.removeAttribute('media');"
          href="{{ asset('theme/App/css/product.css') }}">
@endsection

{{-- <script src="{{ asset('theme/App/js/product_detail.js') }}" defer></script> --}}
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
    @include('theme.components.breadcrumb',[
        'list' => [
            ['href' => '/', 'title' => 'Trang chủ'],
            ['href' => $product->category->full_path, 'title' => $product->category->name],
            ['title' => $product->name],
        ]
    ])
    <!-- Shop details -->
    <div class="shop-detail-page container">
        <div class="row g-0">
            <div class="col-md-6">
                <div class="d-flex flex-column justify-content-center">
                    <div class="product-zoom">
                        <div id="main_image" class="main_image"><img class="img_producto lazy"
                                                                     data-src="{{ $product->image }}"
                                                                     id="main_product_image"></div>
                    </div>
                    {{-- @if ($product->public_gallery_images != null)
                        <div class="thumbnail_images">
                            <ul id="thumbnail">
                                @if ($product->public_gallery_images != null)
                                    <div class="thumbnail_images">
                                        <ul id="thumbnail">
                                            @foreach ($product->public_gallery_images as $g)
                                                <li><img onmouseover="changeImage(this)" class="lazy"
                                                         data-src="{{ $g->path }}"
                                                         width="70"></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </ul>
                        </div>
                    @endif --}}
                </div>
            </div>
            <div class="col-md-6">
                <div class="shop-product__description right-side">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="product-name">{{ $product->name }}</h2>
                    </div>
                    <div class="shop-product__rating">
                        <div class="product-ratting">
                            <span class="spr-badge">
                                <span class="spr-starrating spr-badge-starrating">
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i>
                                </span>
                                @if (count($product->public_comments) > 0)
                                    <span class="spr-badge-caption">Có {{ count($product->public_comments) }} đánh
                                        giá</span>
                                @else
                                    <span class="spr-badge-caption">Chưa có đánh giá</span>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="shop-product__price">
                        <div class="product_price__box d-flex align-items-center">
                            @if ($product->sale_price != null)
                                <span class="product-price" id="ProductPrice">
                                    <span class="money">{{ number_format($product->sale_price, 0, ',', '.') }} đ</span>
                                    <span class="product-old-price">{{ number_format($product->price, 0, ',', '.') }}
                                        đ</span>
                                </span>
                            @else
                                <span class="product-price" id="ProductPrice">
                                    <span class="money">{{ number_format($product->price, 0, ',', '.') }} đ</span>
                                </span>
                            @endif

                        </div>
                    </div>
                    <div class="product_additional_information">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#myModal">
                            <i class="fal fa-envelope"></i>Hỏi về sản phẩm
                        </button>
                    </div>

                    <!-- The Modal -->
                    <div class="hvsp modal fade" id="myModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Bạn cần giải đáp?</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <!-- Modal body -->
                                <div class="modal-body">
                                    <form action="/forms" method="POST">
                                        <input type="hidden" name="product_ask" value="{{ $product->id }}">
                                        <input type="hidden" name="name" value="Hỏi về sản phẩm">
                                        <input type="text" name="customer_name" placeholder="Tên bạn *">
                                        <input type="email" name="customer_email" placeholder="Email của bạn *">
                                        <input type="number" name="customer_phone" placeholder="Số điện thoại *">
                                        <textarea name="customer_note" placeholder="Viết lời nhắn *"></textarea>
                                        @csrf
                                        <button type="submit" class=" btn-custom modal-btn-send">Gửi ngay</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form class="actions" action="/cart/addItem/{{ $product->id }}">
                        <div class="swatch variant_div clearfix Size">
                            <div class="product-detail-option">Size :</div>
                            <div class="variant_inner">
                                <div class="size-boxed">
                                    @if ($varientsProduct->first() == null)
                                        <input type="radio" hidden name="size-radio" value="0" checked>
                                    @endif
                                    @if ($varientsProduct->first() != null)
                                        @foreach ($varientsProduct as $variant)
                                                @foreach($variant->inventory_products as $value)
                                                    {{-- @if($value->quantity>0) --}}
                                                        <input type="radio" id="{{ $variant->name }}" name="size-radio"
                                                               value="{{ $variant->id }}">
                                                        <label for="{{ $variant->name }}">{{ $variant->name }}</label>
                                                    {{-- @endif --}}
                                                    {{-- @if($value->quantity==0)
                                                        <label class="out-of-stock" for="xl">{{ $variant->name }}</label>
                                                    @endif --}}
                                                @endforeach
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="swatch variant_div quantity">
                            <div class="product-detail-option">Số lượng :</div>
                            <div class="variant_inner">
                                <div class="control">
                                    <a style="cursor: pointer;" class="bttn bttn-left" id="minus">
                                        <span>-</span>
                                    </a>
                                    <input type="quantity" name="quantity" class="input" id="input"
                                           value="1">
                                    <a style="cursor: pointer;" class="bttn bttn-right" id="plus">
                                        <span>+</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="btn-group" style="margin-bottom: 15px;">
                            <span class="sticky-productdetail">
                                <button class="btn-custom" type="submit">Thêm giỏ hàng</button>
                            </span>
                            <span style="cursor: pointer;" class="detail-whistlist"><i class="fal fa-heart"></i></span>
                        </div>
                        <div class="accecpt-box">
                            <button id="detailbutton" class="shopdetail-buynow" type="submit"
                                    formaction="/cart/buyItem/{{ $product->id }}">mua ngay
                            </button>
                        </div>
                    </form>

                    <div class="quick-view-other-info pb-0">
                        <table>
                            <tbody>
                            <tr class="single-info">
                                <td class="quickview-title product-sku">Mã sản phẩm:</td>
                                <td class="quickview-value variant-sku">{{ $product->code }}</td>
                            </tr>
                            <tr class="single-info">
                                <td class="quickview-title product-sku">Nhà cung cấp:</td>
                                <td class="quickview-value"> Lebro</td>
                            </tr>
                            <tr class="single-info">
                                <td class="quickview-title product-sku">Loại:</td>
                                <td class="quickview-value"> {{ $product->category->name }} </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End shop details -->

    <!-- Description & Review Tab -->
    <div class="dere container">
        <div class="row">
            <div class="dere-box">
                <div class="tab">
                    <button class="tablinks" onclick="openProduct(event, 'Description')" id="defaultOpen">Thông tin sản
                        phẩm
                    </button>
                    <button class="tablinks" onclick="openProduct(event, 'Review')">Đánh giá</button>
                </div>

                <div id="Description" class="tabcontent">
                    {!! $product->article ? $product->article->content : '' !!}
                </div>

                <div id="Review" class="tabcontent">
                    <div class="review-content">
                        <ul class="comment-list">
                            @foreach ($product->public_comments as $c)
                                <li class="review as-comment-item">
                                    <div class="as-post-comment">
                                        <div class="comment-avater"><img class="lazy"
                                                                         data-src="https://chichifood.vn/theme/assets/img/testimonial/testi_1_3.jpg"
                                                                         alt="Comment Author"></div>
                                        <div class="comment-content">
                                            <h4 class="name">{{ explode(',', $c->author)[0] }}</h4><span
                                                    class="commented-on"><i
                                                        class="fal fa-calendar-alt"></i>{{ $c->created_at->format('d-m-Y') }}</span>
                                            <div class="star-rating" role="img" aria-label="Rated 5.00 out of 1">
                                                <span style="width:100%"></span>
                                            </div>
                                            <p class="text">{{ $c->content }}</p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @if ($product->article != null)
                        <form action="/comments" method="POST">
                            <h3>Viết đánh giá</h3>
                            <input type="text" name="article_id" value="{{ $product->article->id }}" hidden>
                            <input type="text" name="author" placeholder="Điền tên của bạn" required>
                            <input type="email" name="email" placeholder="Điền email của bạn" required>
                            <textarea name="content" placeholder="Viết đánh giá" class="form-control"
                                      required></textarea>
                            @csrf
                            <button type="submit" class="btn-custom">thêm đánh giá</button>
                        </form>
                    @endif
                </div>

            </div>
        </div>
    </div>
    <!-- End Description & Review Tab -->


    <!-- SP đề xuất -->
    @if ($related_products != null)
        <section class="more-product container">
            <div class="row">
                <div class="section-title mb-30 text-center">
                    <h4>Đề xuất cho bạn</h4>
                </div>
                <section class="section-products">

                    <div class="container">
                        <div class="row">
                            <!-- Single Product -->
                            @foreach ($related_products as $r)
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <div id="{{ $r->slug }}" class="single-product">
                                        <div class="part-1">
                                            <div class="product-img-hd"
                                                 onclick="redirectDetail('/product_categories/{{ $r->category_slug }}/products/{{ $r->slug }}')">
                                                <img class="lazy" src="{{\App\Utils\ImageUtil::getMinUrl($r->image)}}"
                                                     data-src="{{$r->image}}"></div>

                                            @foreach ($r->tags as $r->tag)
                                                @if ($r->tag->summary == 'Sale' || $r->tag->summary == 'Mới' || $r->tag->summary == 'Hết hàng')
                                                    @if ($r->tag->summary == 'Sale')
                                                        <span
                                                                class="sale discount">{{ explode(' ', $r->tag->name)[1] }}</span>
                                                        <span
                                                                class="discount">{{ explode(' ', $r->tag->name)[0] }}</span>
                                                    @endif
                                                    @if ($r->tag->summary != 'Sale')
                                                        <span class="new">{{ $r->tag->name }}</span>
                                                    @endif
                                                @endif
                                            @endforeach
                                            <ul>
                                                <li><a href="javascript:;"><i class="far fa-heart"></i></a></li>
                                                <li><a href="javascript:;"
                                                       onclick="redirectDetail('/product_categories/{{ $r->category_slug }}/products/{{ $r->slug }}')"><i
                                                                class="far fa-shopping-bag"></i></a></li>
                                            </ul>
                                            <ul class="hover-size">
                                                @foreach (\App\Utils\Caches\VariantUtil::getInstance()->getVariant($r->id) as $item)
                                                    <li><a href="javascript:;">
                                                            <p>{{ $item->name }}</p>
                                                        </a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="part-2">
                                            <h3 class="product-title"><a
                                                        href="/product_categories/{{ $r->category_slug }}/products/{{ $r->slug }}">{{ $r->name }}</a>
                                            </h3>
                                            @if ($r->sale_price != null)
                                                <h4 class="product-price">
                                                    {{ number_format($r->sale_price, 0, ',', '.') }} đ</h4>
                                                <h4 class="product-old-price">
                                                    {{ number_format($r->price, 0, ',', '.') }} đ</h4>
                                            @else
                                                <h4 class="product-price">
                                                    {{ number_format($r->price, 0, ',', '.') }} đ</h4>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                        <!-- Single Product -->
                        </div>
                    </div>
                </section>
            </div>
        </section>
    @endif

@endsection

@section('scripts')
    <script src="{{ asset('theme/App/js/product_detail.js') }}"></script>
@endsection
