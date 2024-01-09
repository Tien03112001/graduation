@extends('theme.components.layout')
@section('title', 'Thanh toán')
@section('styles')
    <link rel="stylesheet" media="print" onload="this.onload=null;this.removeAttribute('media');"
        href="{{ asset('theme/App/css/checkout.css') }}">
@endsection

@section('content')
    @if (session('msg_success'))
        <div class="alert alert-success">
            <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
            {{ session('msg_success') }}
            {{-- <a href="/products" class="as-btn">Tiếp tục mua sắm</a> --}}
        </div>
    @endif
    <div class="breadcrumb-area overlay-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-left ">
                    <nav class="" role="navigation" aria-label="breadcrumbs">
                        <ul class="breadcrumb-list">
                            <li> <a href="Trang chủ.html" title="Back to the home page">Trang chủ</a></li>
                            <li> <span>Thanh toán</span></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- End breadcrumb -->
    <!-- Check out page -->
    <form method="POST" action="/orders/create" class="checkout-info">
        <section class="checkout-page container">
            <div class="row">
                <div class="col-md-5">

                    @if (isset($product))
                        <input type="text" name="buyNow" id="buyNow" hidden value="buyNow">
                    @endif
                    <div class="checkout-left">
                        <h3 class="checkout-title">Thông Tin Thanh Toán</h3>

                        <input type="text" name="customer_name"
                            placeholder="Tên của bạn *" value="{{ old('customer_name') }}" required>
                        <input type="number" name="customer_phone"
                            placeholder="Số điện thoại *" value="{{ old('customer_phone') }}" required>
                        <input type="email" name="customer_email"
                            placeholder="Điền email *" value="{{ old('customer_email') }}" required>
                        <input type="text" name="customer_address"
                            placeholder="Điền địa chỉ chi tiết *" value="{{ old('customer_address') }}" required>
                        <select class="form-control" id="province_id" type="text"
                            name="province_id" onchange="onProvinceIdChange()" required>
                            <option value="">Chọn tỉnh/TP</option>
                            @foreach ($provinces as $province)
                                <option value="{{ $province['id'] }}">{{ $province['name'] }}</option>
                            @endforeach
                        </select>
                        <select class="form-control" name="district_id" id="district_id"
                            onchange="onDistrictIdChange()" required>
                            <option value="">Chọn quận/huyện</option>
                        </select>
                        <select class="form-control" name="ward_id" id="ward_id"
                            onchange="getFee()" required>
                            <option value="">Chọn xã/phường</option>
                        </select>
                        <input type="hidden" name="shipping_fee" value="0" id="shipping_fee" required>
                        <div class="checkout-cart-mobile">
                            <h3 class="checkout-title" style="margin-bottom: 0;">Thông Tin Đơn Hàng</h3>
                            <table class="cart_table_mobile">
                                <tbody>
                                    @if (!isset($product))
                                        @foreach ($cart->getItems() as $productId => $item)
                                            <tr class="cart_item">
                                                <td>
                                                    <p class="cart-mb-tb-title">Ảnh</p>
                                                    <a class="cart-productimage" href="javascript:void(0)">
                                                        <img width="91" height="91"
                                                            src="{{ $item['item']['image'] }}" alt="Image"
                                                            onclick="redirectDetail('/product_categories/{{ $item['item']['category_slug'] }}/products/{{ $item['item']['slug'] }}')">
                                                    </a>
                                                </td>
                                                <td>
                                                    <p class="cart-mb-tb-title">Tên</p>
                                                    <a class="cart-productname"
                                                        href="/product_categories/{{ $item['item']['category_slug'] }}/products/{{ $item['item']['slug'] }}">
                                                        @if ($item['size'] == '0')
                                                            {{ $item['item']['name'] }}
                                                        @endif
                                                        @if ($item['size'] != '0')
                                                            {{ $item['item']['name'] }}
                                                            <p style="color:#808080">size {{\App\Utils\Caches\VariantUtil::getInstance()->getNameVariantById($item['size'])}}</p>
                                                        @endif
                                                    </a>
                                                </td>
                                                <td>
                                                    <p class="cart-mb-tb-title">Giá</p>
                                                    <span
                                                        class="amount"><bdi>{{ number_format($item['unit_price'], 0, ',', '.') }}đ<span></span></bdi></span>
                                                </td>
                                                <td>
                                                    <p class="cart-mb-tb-title">Số lượng</p>

                                                    <strong class="product-quantity">{{ $item['quantity'] }}</strong>
                                                </td>
                                                <td>
                                                    <p class="cart-mb-tb-title">Thành tiền</p>
                                                    <bdi>{{ number_format($item['amount'], 0, ',', '.') }}đ<span></span></bdi>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    @if (isset($product))
                                        <input type="text" name="product_id" id="product_ids" hidden
                                            value="{{ $product->id }}">
                                        <tr class="cart_item">
                                            <td>
                                                <p class="cart-mb-tb-title">Ảnh</p>
                                                <a class="cart-productimage" href="javascript:void(0)">
                                                    <img width="91" height="91" src="{{ $product->image }}"
                                                        alt="Image">
                                                </a>
                                            </td>
                                            <td>
                                                <p class="cart-mb-tb-title">Tên</p>
                                                <input type="text" name="product_nameP" value="{{ $product->name }}"
                                                    hidden>
                                                <input type="text" name="product_variantP"
                                                    value="{{ Request::get('size-radio') }}" hidden>
                                                @if (Request::get('size-radio') != '0')
                                                    <a class="cart-productname" href="javascript:;">{{ $product->name }}
                                                        - Size {{\App\Utils\Caches\VariantUtil::getInstance()->getNameVariantById(Request::get('size-radio'))}}</a>
                                                @endif
                                                @if (Request::get('size-radio') == '0')
                                                    <a class="cart-productname"
                                                        href="javascript:void(0)">{{ $product->name }}</a>
                                                @endif

                                            </td>
                                            <td>
                                                <p class="cart-mb-tb-title">Giá</p>
                                                <span
                                                    class="amount"><bdi>{{ number_format($product->sale_price, 0, ',', '.') }}đ<span></span></bdi></span>
                                            </td>
                                            <td>
                                                <p class="cart-mb-tb-title">Số lượng</p>
                                                <input type="number" name="quantity" value="{{ $quantity }}"
                                                    hidden>
                                                <strong class="product-quantity">{{ $quantity }}</strong>
                                            </td>
                                            <td>
                                                <p class="cart-mb-tb-title">Thành tiền</p>
                                                <bdi>{{ number_format($product->sale_price * $quantity, 0, ',', '.') }}đ<span></span></bdi>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                                @if (isset($product))
                                    <tfoot class="checkout-ordertable">
                                        <tr class="cart-subtotal">
                                            <td>
                                                <p class="cart-mb-tb-title">Đơn hàng</p>
                                                <span class="woocommerce-Price-amount amount"><bdi>
                                                        {{ number_format($product->sale_price * $quantity, 0, ',', '.') }}<span
                                                            class="woocommerce-Price-currencySymbol">đ</span></bdi></span>
                                            </td>
                                        </tr>
                                        <tr class="woocommerce-shipping-totals shipping">
                                            <td>
                                                <p class="cart-mb-tb-title">Phí ship</p>
                                                <input type="hidden" name="shippingFeeDisplayN"
                                                    value="{{ $product->sale_price * $quantity }}"
                                                    id="shippingFeeDisplayN">
                                                <bdi id="shippingFeeDisplay">0<span
                                                        class="woocommerce-Price-currencySymbol">đ</span></bdi>
                                            </td>
                                        </tr>
                                        <tr class="order-total">
                                            <td>
                                                <p class="cart-mb-tb-title"><b>Thanh toán</b></p>
                                                <input type="hidden" name="total_money"
                                                    value="{{ $product->sale_price * $quantity }}" id="total_money">
                                                <input type="hidden" name="get_totalN"
                                                    value="{{ $product->sale_price * $quantity }}" id="get_totalN">
                                                <span class="woocommerce-Price-amount amount"><bdi
                                                        id="get_total">{{ number_format($product->sale_price * $quantity, 0, ',', '.') }}<span
                                                            class="woocommerce-Price-currencySymbol"></span></bdi></span></strong>
                                            </td>
                                        </tr>
                                    </tfoot>
                                @endif
                                @if (!isset($product))
                                    <tfoot class="checkout-ordertable">
                                        <tr class="cart-subtotal">
                                            <td>
                                                <p class="cart-mb-tb-title">Đơn hàng</p>
                                                <span class="woocommerce-Price-amount amount"><bdi>
                                                        {{ number_format($cart->getTotalAmount(), 0, ',', '.') }}<span
                                                            class="woocommerce-Price-currencySymbol">đ</span></bdi></span>
                                            </td>
                                        </tr>
                                        @if($promotions)
                                            @php
                                                $freeship=0;
                                            @endphp
                                            @foreach($promotions as $promotion)
                                                @if($promotion['type']=="3")
                                                    @if($cart->getTotalQuantity() >= $promotion['min_products_count'])
                                                        @if($cart->getTotalAmount() >= $promotion['min_order_value'])
                                                            @php
                                                                $freeship=1;
                                                            @endphp
                                                        @endif
                                                    @endif
                                                @endif
                                                @if($promotion['type']==4)
                                                    @if($cart->getTotalAmount()>=$promotion['min_order_value'])
                                                            <tr class="woocommerce-shipping-totals shipping">
                                                                <td>
                                                                    <p class="cart-mb-tb-title" >Giá giảm</p>-
                                                                    <input type="hidden" id="discount_valueM" value="{{$promotion['discount_value']}}">
                                                                    <bdi >{{ number_format($promotion['discount_value'], 0, ',', '.') }}<span
                                                                            class="woocommerce-Price-currencySymbol">đ</span></bdi>
                                                                </td>
                                                            </tr>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                        <tr class="woocommerce-shipping-totals shipping">
                                            <td>
                                                <p class="cart-mb-tb-title">Phí ship</p>
                                                <bdi id="shippingFeeDisplay">{{ number_format($cart->getShippingFee(), 0, ',', '.') }}<span
                                                        class="woocommerce-Price-currencySymbol">đ</span></bdi>
                                            </td>
                                        </tr>
                                        <tr class="order-total">
                                            <td>
                                                <p class="cart-mb-tb-title"><b>Thanh toán</b></p>
                                                <input type="hidden" name="total_money" value="{{ $cart->getTotal() }}"
                                                    id="total_money">
                                                <span class="woocommerce-Price-amount amount"><bdi
                                                        id="get_total">{{ number_format($cart->getTotal(), 0, ',', '.') }}đ<span
                                                            class="woocommerce-Price-currencySymbol"></span></bdi></span></strong>
                                            </td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="checkout-right">
                        <h3 class="checkout-title">Thông Tin Đơn Hàng</h3>
                        <table class="cart_table">
                            <thead>
                                <tr style="text-align: center">
                                    <th class="cart-col-image">Ảnh</th>
                                    <th class="cart-col-productname">Tên sản phẩm</th>
                                    <th class="cart-col-price">Giá</th>
                                    <th class="cart-col-quantity">Số lượng</th>
                                    <th class="cart-col-total">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($product))
                                    <input type="text" name="product_id" id="product_ids" hidden
                                        value="{{ $product->id }}">
                                    <tr class="cart_item">
                                        <td data-title="Ảnh">

                                            <img src="{{ $product->image }}" alt="Image"
                                                onclick="redirectDetail('/product_categories/{{ $product->category_slug }}/products/{{ $product->slug }}')">
                                        </td>
                                        <td data-title="Tên">
                                            <input type="text" name="product_name"
                                                value="{{ $product->name}}"
                                                hidden>
                                                <input type="text" name="product_variant"
                                                value="{{ Request::get('size-radio')}}"
                                                hidden>
                                            @if (Request::get('size-radio') != '0')
                                                <a class="cart-productname"
                                                    href="/product_categories/{{ $product->category_slug }}/products/{{ $product->slug }}">
                                                    {{ $product->name }}
                                                    <p style="color:#808080"> size {{\App\Utils\Caches\VariantUtil::getInstance()->getNameVariantById(Request::get('size-radio'))}}</p>
                                                </a>
                                            @endif
                                            @if (Request::get('size-radio') == '0')
                                                <a class="cart-productname"
                                                    href="/product_categories/{{ $product->category_slug }}/products/{{ $product->slug }}">
                                                    {{ $product->name }}
                                                </a>
                                            @endif
                                        </td>
                                        <td data-title="Giá">
                                            <span class="amount"><bdi>{{ number_format($product->sale_price, 0, ',', '.') }}đ<span>
                                                    </span></bdi></span>
                                        </td>
                                        <td data-title="Số lượng">
                                            <input type="number" name="quantity" value="{{ $quantity }}" hidden>
                                            <strong class="product-quantity">{{ $quantity }}</strong>
                                        </td>
                                        <td data-title="Tổng">
                                            <bdi>{{ number_format($product->sale_price * $quantity, 0, ',', '.') }}đ<span></span></bdi>
                                        </td>
                                    </tr>
                                @endif
                                @if (!isset($product))
                                    @foreach ($cart->getItems() as $productId => $item)
                                        <tr class="cart_item">
                                            <td data-title="Ảnh">

                                                <img src="{{ $item['item']['image'] }}" alt="Image"
                                                    onclick="redirectDetail(' /product_categories/{{ $item['item']['category_slug'] }}/products/{{ $item['item']['slug'] }}')">

                                            </td>
                                            <td data-title="Tên">
                                                <a class="cart-productname"
                                                    href=" /product_categories/{{ $item['item']['category_slug'] }}/products/{{ $item['item']['slug'] }}">
                                                    @if ($item['size'] == '0')
                                                        {{ $item['item']['name'] }}
                                                    @endif
                                                    @if ($item['size'] != '0')
                                                        {{ $item['item']['name'] }}
                                                        <p style="color:#808080">size {{\App\Utils\Caches\VariantUtil::getInstance()->getNameVariantById($item['size'])}}</p>
                                                    @endif
                                                </a>
                                            </td>
                                            <td data-title="Giá">
                                                <span class="amount"><bdi>{{ number_format($item['unit_price'], 0, ',', '.') }}đ<span>
                                                        </span></bdi></span>
                                            </td>
                                            <td data-title="Số lượng">
                                                <strong class="product-quantity">{{ $item['quantity'] }}</strong>
                                            </td>
                                            <td data-title="Tổng">
                                                <bdi>{{ number_format($item['amount'], 0, ',', '.') }}đ<span></span></bdi>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                            @if (isset($product))
                                {{-- <tfoot class="checkout-ordertable">
                                <tr class="cart-subtotal">
                                    <th>Đơn hàng</th>
                                    <td data-title="Đơn hàng" colspan="4">
                                        <span class="woocommerce-Price-amount amount"><bdi>
                                                {{ number_format($product->price_sale*$quantity, 0, ',', '.') }}đ
                                                <span class="woocommerce-Price-currencySymbol"></span></bdi>
                                        </span>
                                    </td>
                                </tr>
                                <tr class="woocommerce-shipping-totals shipping">
                                    <th>Phí ship</th>
                                    <td data-title="Phí ship" colspan="4" id="shippingFeeDisplayP"><bdi>
                                            0đ
                                            <span class="woocommerce-Price-currencySymbol"> </span></bdi>
                                    </td>
                                </tr>
                                <tr class="order-total">
                                    <th><b>Thanh toán</b></th>
                                    <td data-title="Thanh toán" colspan="4">
                                        <strong>
                                            <input type="hidden" name="total_money" value="{{ $product->price_sale*$quantity }}"
                                                id="total_money">
                                            <span class="woocommerce-Price-amount amount"><bdi
                                                    id="get_totalP">{{ number_format($product->price_sale*$quantity, 0, ',', '.') }}đ<span
                                                        class="woocommerce-Price-currencySymbol"></span></bdi></span></strong>
                                        </strong>
                                    </td>
                                </tr>
                            </tfoot> --}}
                                <tfoot class="checkout-ordertable">
                                    <tr class="cart-subtotal">
                                        <th>
                                            <p class="cart-mb-tb-title">Đơn hàng</p>
                                        </th>
                                        <td colspan="4">
                                            <span class="woocommerce-Price-amount amount"><bdi>
                                                    {{ number_format($product->sale_price * $quantity, 0, ',', '.') }}<span
                                                        class="woocommerce-Price-currencySymbol">đ</span></bdi></span>
                                        </td>
                                    </tr>

                                    <tr class="woocommerce-shipping-totals shipping">
                                        <th>
                                            <p class="cart-mb-tb-title">Phí ship</p>
                                        </th>
                                        <td colspan="4">
                                            <bdi id="shippingFeeDisplayP">0<span
                                                    class="woocommerce-Price-currencySymbol">đ</span></bdi>
                                        </td>
                                    </tr>
                                    <tr class="order-total">
                                        <th>
                                            <p class="cart-mb-tb-title"><b>Thanh toán</b></p>
                                        </th>
                                        <td colspan="4">
                                            <input type="hidden" name="total_money"
                                                value="{{ $product->sale_price * $quantity }}" id="total_money">
                                            <span class="woocommerce-Price-amount amount"><bdi
                                                    id="get_totalP">{{ number_format($product->sale_price * $quantity, 0, ',', '.') }}<span
                                                        class="woocommerce-Price-currencySymbol"></span></bdi></span></strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            @endif
                            @if (!isset($product))
                                <tfoot class="checkout-ordertable">
                                    <tr class="cart-subtotal">
                                        <th>Đơn hàng</th>
                                        <td data-title="Đơn hàng" colspan="4">
                                            <span class="woocommerce-Price-amount amount"><bdi>
                                                    {{ number_format($cart->getTotalAmount(), 0, ',', '.') }}đ
                                                    <span class="woocommerce-Price-currencySymbol"></span></bdi>
                                            </span>
                                        </td>
                                    </tr>
                                    @if($promotions)
                                        @php
                                            $freeship=0;
                                        @endphp
                                        @foreach($promotions as $promotion)
                                            @if($promotion['type']=="3")
                                                @if($cart->getTotalQuantity() >= $promotion['min_products_count'])
                                                    @if($cart->getTotalAmount() >= $promotion['min_order_value'])
                                                        @php
                                                            $freeship=1;
                                                        @endphp
                                                    @endif
                                                @endif
                                            @endif
                                            @if($promotion->type==4)
                                                @if($cart->getTotalAmount() >= $promotion['min_order_value'])
                                                    <tr class="woocommerce-shipping-totals shipping">
                                                        <th>Giá giảm</th>
                                                        <td data-title="Phí ship" colspan="4" >-<bdi>
                                                                <input type="hidden" id="discount_value" name="discount_value" value="{{$promotion['discount_value']}}">
                                                                <span>{{ number_format($promotion['discount_value'], 0, ',', '.') }}</span>đ
                                                                <span class="woocommerce-Price-currencySymbol"> </span></bdi>
                                                        </td>
                                                    </tr>
                                                @endif

                                            @endif
                                        @endforeach
                                    @endif
                                    <tr class="woocommerce-shipping-totals shipping">
                                        <th>Phí ship</th>
                                        <input type="hidden" id="freeship" name="freeship" value="{{$freeship}}">
                                        <td data-title="Phí ship" colspan="4" id="shippingFeeDisplayP"><bdi>
                                                {{ number_format($cart->getShippingFee(), 0, ',', '.') }}đ
                                                <span class="woocommerce-Price-currencySymbol"> </span></bdi>
                                        </td>
                                    </tr>
                                    <tr class="order-total">
                                        <th><b>Thanh toán</b></th>
                                        <td data-title="Thanh toán" colspan="4">
                                            <strong>
                                                <input type="hidden" name="total_money" value="{{ $cart->getTotal() }}"
                                                    id="total_money">
                                                <span class="woocommerce-Price-amount amount"><bdi
                                                        id="get_totalP">{{ number_format($cart->getTotal(), 0, ',', '.') }}đ<span
                                                            class="woocommerce-Price-currencySymbol"></span></bdi></span></strong>
                                            </strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="payment-collapse col-md-5">
                    <h3 class="checkout-title">Chọn phương thức thanh toán</h3>
                    @foreach ($paymentMethods as $p)
                        @if ($p->name == \App\Common\Enum\PaymentMethodEnum::QR)
                            <div class="pay-select">
                                <input class="payment-radio" data-bs-toggle="collapse" href="#collapsepay1"
                                    aria-expanded="false" name="paymentbar" type="radio" value="{{ $p->name }}">
                                <label>QR-Code</label><br>
                                <div id="collapsepay1" class="collapse">
                                    <div class="card-body">
                                        <img src="{{ $p->config }}">
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($p->name == \App\Common\Enum\PaymentMethodEnum::MANUAL)
                            <div class="pay-select">
                                <input class="payment-radio" data-bs-toggle="collapse" href="#collapsepay2"
                                    aria-expanded="false" name="paymentbar" type="radio" value="{{ $p->name }}">
                                <label>Chuyển khoản</label><br>
                                <div id="collapsepay2" class="collapse">
                                    <div class="card-body">
                                        <ul>
                                            @if (isset($manual_config['accounts']))
                                                @foreach ($manual_config['accounts'] as $account)
                                                    <li>Tên tài khoản: {{ $account['owner_name'] }} </li>
                                                    <li>Số tài khoản: {{ $account['bank_account'] }} </li>
                                                    <li>Ngân hàng: {{ $account['bank_name'] }} </li>
                                                    <li>Chi nhánh: {{ $account['bank_branch'] }} </li>
                                                    <li>Nội dung: Tên + Số điện thoại</li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($p->name == \App\Common\Enum\PaymentMethodEnum::COD)
                            <div class="pay-select">
                                <input class="payment-radio" name="paymentbar" data-bs-toggle="collapse" type="radio"
                                    value="{{ $p->name }}">
                                <label>Thanh toán khi nhận hàng</label><br>
                            </div>
                        @endif
                        @if ($p->name == \App\Common\Enum\PaymentMethodEnum::VNPAY)
                            <div class="pay-select">
                                <input class="payment-radio" data-bs-toggle="collapse" href="#collapsepay4"
                                    aria-expanded="false" name="paymentbar" type="radio" value="{{ $p->name }}">
                                <label>VN-Pay</label><br>
                                <div id="collapsepay4" class="collapse">
                                    <div class="card-body">
                                        <img src="{{asset("theme/App/img/hero/lebro-vnpay.webp")}}">
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="payment-btn">
                    <div>

                        @error('paymentbar')
                            <i class="error-noti">{{ $message }}</i>
                        @enderror
                    </div>
                    <button type="submit" class="btn-custom">Đặt hàng</button>
                </div>
            </div>
        </section>
        @csrf
    </form>

@endsection

@section('scripts')
    <script async src="{{ asset('js/eziweb.js') }}" type="text/javascript"></script>
    <script src="{{ asset('theme/App/js/checkout.js') }}" defer></script>
@endsection
