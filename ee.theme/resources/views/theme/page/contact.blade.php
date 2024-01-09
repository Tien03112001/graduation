@extends('theme.components.layout')
@section('styles')
    <link rel="stylesheet" media="print" onload="this.onload=null;this.removeAttribute('media');"
          href="{{ asset('theme/App/css/contact.css') }}">
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
    <!-- Breadcrumb -->
    <div class="breadcrumb-area overlay-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-left ">
                    <nav class="" role="navigation" aria-label="breadcrumbs">
                        <ul class="breadcrumb-list">
                            <li><a href="/" title="Back to the home page">Trang chủ</a></li>
                            <li><span>Liên hệ</span></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- End breadcrumb -->

    <!-- Contact page -->
    <div class="contact-page">
        <section class="section-contact-title container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="contact-title text-center">
                        <h2>thông tin liên hệ</h2>
                    </div>
                </div>
            </div>
        </section>

        <div class="icon-box-area">
            <div class="container">
                <div class="row">
                    @foreach ($contact as $item)
                        @if ($item['name']=="ĐỊA CHỈ")
                            <div class="contact-icon col-lg-4 col-md-4">
                                <div class="single-icon-box">
                                    {{-- <div class="icon-box-icon"> {!!$item['icon']!!}</div> --}}
                                    <div class="icon-box-content">
                                        <h3 class="title">{{$item['name']}}</h3>
                                        <p class="content" style="max-width: 204px;">{!! $item['content']!!}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($item['name']!="ĐỊA CHỈ")
                            <div class="contact-icon col-lg-4 col-md-4">
                                <div class="single-icon-box">
                                    {{-- <div class="icon-box-icon"> {!!$item['icon']!!}</div> --}}
                                    <div class="icon-box-content">
                                        <h3 class="title">{{$item['name']}}</h3>
                                        <p class="content">{!! $item['content']!!}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <section class="map-block">
            <div class="container">
                {{-- <div class="contact-map">
                    {!! $companyInformation['Vị trí'] !!}
                </div> --}}
            </div>
        </section>

        <section class="contact-page-form">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="contact-title text-center">
                            <h2>ý kiến của bạn</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="contact-form-area">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <div class="custom-lebro-form contact-form">
                                <form method="post" action="/forms" id="contact_form"
                                      accept-charset="UTF-8" class="contact-form">
                                    <input type="hidden" name="name"
                                           value="Ý kiến khách hàng">
                                    <div class="row">
                                        <div class="col-12"></div>
                                        <div class="contact-input col-md-6 mb-40"><input type="text" required=""
                                                                                         placeholder="Tên của bạn *"
                                                                                         class="" name="customer_name"
                                                                                         id="ContactFormName"></div>
                                        <div class="contact-input col-md-6 mb-40"><input type="email" required=""
                                                                                         placeholder="Địa chỉ email *"
                                                                                         class="" name="customer_email"
                                                                                         id="ContactFormEmail"></div>
                                        <div class="col-lg-12 mb-40"><textarea placeholder="Ý kiến của bạn *"
                                                                               class="custom-textarea"
                                                                               name="customer_note"
                                                                               id="ContactFormMessage"></textarea></div>
                                        <div class="col-lg-12 text-center">
                                            <button type="submit"
                                                    class="btn-custom">gửi ý kiến
                                            </button>
                                        </div>
                                    </div>
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- End contact page -->


@endsection

@section('scripts')

@endsection
