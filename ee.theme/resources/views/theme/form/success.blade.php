@extends('theme.components.layout')

@section('content')
    <section>
        <div class="container success">
            <div class="feature-inner">
                <h2 class="section-title text-center">Thành công</h2>
                <div class="row">
                    <div class="col-12">
                        <p class="text-center">Cám ơn bạn đã gửi thông tin. Chúng tôi sẽ liên hệ lại tới bạn sau</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            $(document).ready(function() {
                setTimeout(function() {
                    window.history.go(-1);
                }, 2000);
            });
        });
    </script>
@endsection
