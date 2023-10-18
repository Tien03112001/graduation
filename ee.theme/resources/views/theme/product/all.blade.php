@extends('theme.components.layout')
@section('styles')
    <link rel="stylesheet" media="print" onload="this.onload=null;this.removeAttribute('media');"
          href="{{ asset('theme/App/css/product_category.css') }}">
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
    @include('theme.components.breadcrumb',[
        'list' => [
            ['href' => '/', 'title' => 'Trang chủ'],
            ['title' => 'Tất cả sản phẩm'],
        ]
    ])
    <div class="shop-page-header">
        <div class=" container ">
            <div class="single-icon filter-dropdown">
                <label for="SortBy">Sắp xếp theo:</label>
                <select name="SortBy" id="SortBy">
                    <option @if (Request::get('sort_by') == 'manual') selected @endif value="manual">Mới nhất</option>
                    <option @if (Request::get('sort_by') == 'asc') selected @endif value="asc">Giá tăng dần</option>
                    <option @if (Request::get('sort_by') == 'desc') selected @endif value="desc">Giá giảm dần</option>
                </select>
            </div>
            <div class="single-icon advance-filter-icon d-lg-none">
                <a id="sidebar-filter-active-btn" href="javascript:;" type="button" data-bs-toggle="offcanvas"
                   data-bs-target="#filtersideNav">
                    <i class="fal fa-sliders-v"></i>Bộ lọc
                </a>
            </div>
            <div class="navside offcanvas offcanvas-end" id="filtersideNav">
                <div class="offcanvas-header">
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body">
                    <div id="accordion" class="shop-sidebar">
                        <a class="shop-sidebar-menu" data-bs-toggle="collapse" href="#collapseMenu1"
                           aria-expanded="true">Danh Mục</a>

                        <div id="collapseMenu1" class="collapse show">
                            <div class="card-body">
                                @foreach ($menus['Menu đầu trang'] as $key => $item)
                                    @if ($item['children'] == null and $item['parent_id'] == 0)
                                        <a href="{{ $item['url'] }}">{{ $item['name'] }}</a>
                                    @endif
                                    @if ($item['children'] != null)
                                        <a class="megasideshop collapsed" data-bs-toggle="collapse"
                                           href="#collapseMenusp">
                                            {{ $item['name'] }}
                                        </a>
                                        <div id="collapseMenusp" class="collapse" data-bs-parent="#accordion">
                                            <div class="card-body children">
                                                @foreach ($item['children'] as $c)
                                                    <a href="{{ $c['url'] }}">{{ $c['name'] }}</a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        {{-- <a class="shop-sidebar-menu" data-bs-toggle="collapse" href="#collapseMenu2"
                            aria-expanded="true">Khả dụng</a>
                        <div id="collapseMenu2" class="collapse show">
                            <div class="card-body">
                                <ul>
                                    <li>
                                        <input onclick="setParamsStock('availability','in_stock')" @if (Request::get('availability') == 'in_stock')
                                            checked
                                        @endif class="sidebar-radio" type="radio">
                                        <label>Còn hàng</label><br>
                                    </li>
                                    <li>
                                        <input onclick="setParamsStock('availability','out_stock')" @if (Request::get('availability') == 'out_stock')
                                        checked
                                        @endif class="sidebar-radio" type="radio">
                                        <label >Hết hàng</label><br>
                                    </li>
                                </ul>
                            </div>
                        </div> --}}

                        <a class="shop-sidebar-menu" data-bs-toggle="collapse" href="#collapseMenu3"
                           aria-expanded="true">Giá</a>
                        <div id="collapseMenu3" class="collapse show">
                            <div class="card-body">
                                <label>Từ</label>
                                <div class="filter_range_input">
                                    <input class="price-filter" id="start_price_m" type="number" placeholder="0"
                                           @if (!Request::get('start_price')) @else
                                           value="{{ Request::get('start_price') }}" @endif>
                                </div>
                                <label>Đến d</label>
                                <div class="filter_range_input">
                                    <input class="price-filter" id="end_price_m" type="number" placeholder="1.100.000"
                                           @if (!Request::get('end_price')) @else
                                           value="{{ Request::get('end_price') }}" @endif>
                                </div>
                                <button class="price-filter-btn" onclick="filterPrice_m()">
                                    <span>Lọc</span>
                                </button>
                            </div>
                        </div>

                        <a class="shop-sidebar-menu" data-bs-toggle="collapse" href="#collapseMenu4"
                           aria-expanded="true">Size</a>
                        <div id="collapseMenu4" class="collapse show">
                            <div class="card-body">
                                <ul>
                                    <li>
                                        <input class="sidebar-radio" name="sizeshopsidebar" type="checkbox"
                                               onclick="setParamsSize('SizeS',1)"
                                               @if (Request::get('SizeS') == 1) checked @endif>
                                        <label style="margin-left: 5px;" style="margin-left: 5px;">S</label><br>
                                    </li>
                                    <li>
                                        <input class="sidebar-radio" name="sizeshopsidebar" type="checkbox"
                                               onclick="setParamsSize('SizeM',1)"
                                               @if (Request::get('SizeM') == 1) checked @endif>
                                        <label style="margin-left: 5px;" style="margin-left: 5px;">M</label><br>
                                    </li>
                                    <li>
                                        <input class="sidebar-radio" name="sizeshopsidebar" type="checkbox"
                                               onclick="setParamsSize('SizeL',1)"
                                               @if (Request::get('SizeL') == 1) checked @endif>
                                        <label style="margin-left: 5px;" style="margin-left: 5px;">L</label><br>
                                    </li>
                                    <li>
                                        <input class="sidebar-radio" name="sizeshopsidebar" type="checkbox"
                                               onclick="setParamsSize('SizeXL',1)"
                                               @if (Request::get('SizeXL') == 1) checked @endif>
                                        <label style="margin-left: 5px;" style="margin-left: 5px;">XL</label><br>
                                    </li>
                                    <li>
                                        <input class="sidebar-radio" name="sizeshopsidebar" type="checkbox"
                                               onclick="setParamsSize('SizeXXL',1)"
                                               @if (Request::get('SizeXXL') == 1) checked @endif>
                                        <label style="margin-left: 5px;" style="margin-left: 5px;">XXL</label><br>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <a href onclick="event.preventDefault(); location+='?&page=3'">Like</a> --}}
    <div class="shop-page-content">
        <section class="container">
            <div class="row">
                <div class="col-lg-3 mb-md-80 mb-sm-80 d-none d-lg-block">
                    <div class="offcanvas-body">
                        <div id="accordion" class="shop-sidebar">
                            <a class="shop-sidebar-menu" data-bs-toggle="collapse" href="#collapseMenu1"
                               aria-expanded="true">Danh Mục</a>
                            <div id="collapseMenu1" class="collapse show">
                                <div class="card-body">
                                    @foreach ($menus['Menu đầu trang'] as $key => $item)
                                        @if ($item['children'] == null and $item['parent_id'] == 0)
                                            <a href="{{ $item['url'] }}">{{ $item['name'] }}</a>
                                        @endif
                                        @if ($item['children'] != null)
                                            <a class="megasideshop collapsed" data-bs-toggle="collapse"
                                               href="#collapseMenusp">
                                                {{ $item['name'] }}
                                            </a>
                                            <div id="collapseMenusp" class="collapse" data-bs-parent="#accordion">
                                                <div class="card-body children">
                                                    @foreach ($item['children'] as $c)
                                                        <a href="{{ $c['url'] }}">{{ $c['name'] }}</a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            {{-- <a class="shop-sidebar-menu" data-bs-toggle="collapse" href="#collapseMenu2"
                            aria-expanded="true">Khả dụng</a>
                        <div id="collapseMenu2" class="collapse show">
                            <div class="card-body">
                                <ul>
                                    <li>
                                        <input onclick="setParamsStock('availability','in_stock')" @if (Request::get('availability') == 'in_stock')
                                            checked
                                        @endif class="sidebar-radio" type="radio">
                                        <label>Còn hàng</label><br>
                                    </li>
                                    <li>
                                        <input onclick="setParamsStock('availability','out_stock')" @if (Request::get('availability') == 'out_stock')
                                        checked
                                        @endif class="sidebar-radio" type="radio">
                                        <a >Hết hàng</a><br>
                                    </li>
                                </ul>
                            </div>
                        </div> --}}

                            <a class="shop-sidebar-menu" data-bs-toggle="collapse" href="#collapseMenu3"
                               aria-expanded="true">Giá</a>
                            <div id="collapseMenu3" class="collapse show">
                                <div class="card-body">
                                    <label>Từ</label>
                                    <div class="filter_range_input">
                                        <input class="price-filter" id="start_price" type="number" placeholder="0"
                                               @if (!Request::get('start_price')) @else
                                               value="{{ Request::get('start_price') }}" @endif>
                                    </div>
                                    <label>Đến</label>
                                    <div class="filter_range_input">
                                        <input class="price-filter" id="end_price" type="number"
                                               placeholder="1.100.000"
                                               @if (!Request::get('end_price')) @else
                                               value="{{ Request::get('end_price') }}" @endif>
                                    </div>
                                    <button class="price-filter-btn" onclick="filterPrice()">
                                        <span>Lọc</span>
                                    </button>
                                </div>
                            </div>
                            <a class="shop-sidebar-menu" data-bs-toggle="collapse" href="#collapseMenu4"
                               aria-expanded="true">Size</a>
                            <div id="collapseMenu4" class="collapse show">
                                <div class="card-body">
                                    <ul>
                                        <li>
                                            <input class="sidebar-radio" name="sizeshopsidebar"
                                                   onclick="setParamsSize('SizeS',1)"
                                                   @if (Request::get('SizeS') == 1) checked @endif type="checkbox">
                                            <label style="margin-left: 5px;">S</label><br>
                                        </li>
                                        <li>
                                            <input class="sidebar-radio" name="sizeshopsidebar"
                                                   onclick="setParamsSize('SizeM',1)"
                                                   @if (Request::get('SizeM') == 1) checked @endif type="checkbox">
                                            <label style="margin-left: 5px;">M</label><br>
                                        </li>
                                        <li>
                                            <input class="sidebar-radio" name="sizeshopsidebar"
                                                   onclick="setParamsSize('SizeL',1)"
                                                   @if (Request::get('SizeL') == 1) checked @endif type="checkbox">
                                            <label style="margin-left: 5px;">L</label><br>
                                        </li>
                                        <li>
                                            <input class="sidebar-radio" name="sizeshopsidebar"
                                                   onclick="setParamsSize('SizeXL',1)"
                                                   @if (Request::get('SizeXL') == 1) checked @endif type="checkbox">
                                            <label style="margin-left: 5px;">XL</label><br>
                                        </li>
                                        <li>
                                            <input class="sidebar-radio" name="sizeshopsidebar"
                                                   onclick="setParamsSize('SizeXXL',1)"
                                                   @if (Request::get('SizeXXL') == 1) checked @endif type="checkbox">
                                            <label style="margin-left: 5px;">XXL</label><br>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-12 ">
                    <section class="section-products">
                        <div class="row">
                            <!-- Single Product -->
                            @foreach ($pagination as $key => $product)
                                <div class="col-md-6 col-lg-4 col-xl-4">
                                    <div id="{{ $product->slug }}" class="single-product">
                                        <div class="part-1">
                                            <div class="product-img-hd"
                                                 onclick="redirectDetail('/product_categories/{{ $product->category_slug }}/products/{{ $product->slug }}')">
                                                <img class="lazy"
                                                     src="{{\App\Utils\ImageUtil::getMinUrl($product->image)}}"
                                                     data-src="{{$product->image}}"></div>

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
                                            <h3 class="product-title">{{ $product->name }}</h3>

                                            @if ($product->sale_price == null)
                                                <h4 class="product-price">{{ number_format($product->price) }}đ</h4>
                                            @else
                                                <h4 class="product-price">{{ number_format($product->sale_price) }}
                                                    đ</h4>
                                                <h4 class="product-old-price">{{ number_format($product->price) }}đ</h4>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                    <div class="row">
                        <div class="shop-pagination-block col-lg-12 text-center mt-md-0 mt-sm-0">

                            <div class="default-pagination">
                                <nav class="shop-pagination justify-content-center pagination">
                                    @if ($pagination->isNotEmpty())
                                        <ul class="">
                                            @if ($pagination->previousPageUrl())
                                                <li class=" disabled prev"><a style="cursor:pointer"
                                                                              onclick="setParamsPage('page','{{ (int) Request::get('page') - 1 }}')">
                                                        <span>Trước</span>
                                                    </a>
                                                </li>
                                            @endif
                                            @if (!$pagination->previousPageUrl())
                                                <li class=" disabled prev"><a style="cursor:not-allowed">
                                                        <span>Trước</span>
                                                    </a>
                                                </li>
                                            @endif
                                            @for ($i = 1; $i <= $pagination->lastPage(); $i++)
                                                @if ($pagination->currentPage() == $i)
                                                    <li class="active">
                                                        <a style="cursor:not-allowed">
                                                            <span>{{ $i }}</span>
                                                        </a>
                                                    </li>
                                                @else
                                                    <li>
                                                        <a style="cursor:pointer"
                                                           onclick="setParamsPage('page','{{ $i }}')">
                                                            <span>{{ $i }}</span>
                                                        </a>
                                                    </li>
                                                @endif
                                            @endfor
                                            @if ($pagination->nextPageUrl())
                                                <li class="next">
                                                    <a style="cursor:pointer"
                                                       onclick="setParamsPage('page','{{ (int) Request::get('page') + 1 }}')"
                                                       aria-hidden="true">
                                                        Sau
                                                    </a>
                                                </li>
                                            @endif
                                            @if (!$pagination->nextPageUrl())
                                                <li class="next">
                                                    <a style="cursor:not-allowed" aria-hidden="true">
                                                        Sau
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    @endif
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('theme/App/js/product_category.js') }}"></script>
@endsection
