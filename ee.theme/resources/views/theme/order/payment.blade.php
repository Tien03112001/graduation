@extends('theme.components.layout')

@section('content')
    @if ($order->payment_type == \App\Common\Enum\PaymentMethodEnum::COD)
        {{-- Thanh toan COD --}}
        <div class="check-out-tks container">
            <div class="checkrow row text-center">
                <h3>Cám ơn bạn đã đặt hàng!</h3>
                <p>Đơn hàng của bạn có mã là {{ $order->code }}. Đơn hàng sẽ được gửi đi ngay sau khi
                    chúng
                    tôi sắp hàng xong.</p>
                <a class="checkout-tks-btn btn-custom" href="/products">Tiếp tục mua sắm</a>
            </div>
        </div>
    @elseif($order->payment_type == \App\Common\Enum\PaymentMethodEnum::MANUAL)
        <div class="check-out-tks container">
            <div class="checkrow row text-center">
                <h3>Cám ơn bạn đã đặt hàng!</h3>
                <p>Đơn hàng của bạn có mã là {{ $order->code }}. Vui lòng chuyển khoản vào các tài khoản
                    dưới
                    đây.</p>
                    @foreach ($paymentProcess->getAccounts() as $account)
                        <p>{{ $account->getOwnerName() }}</p>
                        <ul>
                            <li>Ngân hàng: {{ $account->getBankName() }}</li>
                            <li>Tài khoản: {{ $account->getBankAccount() }}</li>
                            <li>Chi nhánh: {{ $account->getBankBranch() }}</li>
                    </ul>
                    @endforeach
                <a class="checkout-tks-btn btn-custom" href="/products">Tiếp tục mua sắm</a>
            </div>
        </div>
        {{-- <p>Cám ơn bạn đã đặt hàng. Đơn hàng của bạn có mã là {{ $order->code }}. Vui lòng chuyển khoản vào các tài khoản
            dưới
            đây:</p>
        @foreach ($paymentProcess->getAccounts() as $account)
            <p>{{ $account->getOwnerName() }}</p>
            <ul>
                <li>Ngân hàng: {{ $account->getBankName() }}</li>
                <li>Tài khoản: {{ $account->getBankAccount() }}</li>
                <li>Chi nhánh: {{ $account->getBankBranch() }}</li>
            </ul>
        @endforeach
        <a href="/products">Tiếp tục mua sắm</a> --}}
    @elseif($order->payment_type == \App\Common\Enum\PaymentMethodEnum::QR)
    <div class="check-out-tks container">
        <div class="checkrow row text-center">
            <h3>Cám ơn bạn đã đặt hàng!</h3>
            <p>Đơn hàng của bạn có mã là {{ $order->code }}. Đơn hàng sẽ được gửi đi ngay sau khi
                chúng
                tôi sắp hàng xong.</p>
            <a class="checkout-tks-btn btn-custom" href="/products">Tiếp tục mua sắm</a>
        </div>
    </div>
    @elseif($order->payment_type == \App\Common\Enum\PaymentMethodEnum::MOMO)
        <p>Cám ơn bạn đã đặt hàng. Đơn hàng của bạn có mã là {{ $order->code }}. Chúng tôi sẽ chuyển đến trang thanh toán
            theo
            phương thức {{ $paymentMethod->name }} sau <span id="timeout">5s</span></p>
    @elseif($order->payment_type == \App\Common\Enum\PaymentMethodEnum::VNPAY)
    <div class="check-out-tks container">
        <div class="checkrow row text-center">
            <h3>Cám ơn bạn đã đặt hàng!</h3>
            <p>Cám ơn bạn đã đặt hàng. Đơn hàng của bạn có mã là {{ $order->code }}. Chúng tôi sẽ chuyển đến trang thanh toán
                theo
                phương thức {{ $paymentMethod->name }} sau <span id="timeout">5s</span> ...</p>
        </div>
    </div>
    @endif

@endsection

@section('scripts')
    <script>
        function redirectToPaymentUrl(url, updateElementId = 'timeout', timeout = 5) {
            console.log(url);
            let remainTimeout = timeout;
            setInterval(() => {
                remainTimeout--;
                document.getElementById(updateElementId).innerHTML = remainTimeout + 's';
            }, 1000);
            setTimeout(function() {
                window.location.href = url;
            }, timeout * 1000);
        }
    </script>
    @if ($order->payment_type == \App\Common\Enum\PaymentMethodEnum::MOMO)
        <script>
            redirectToPaymentUrl('{!! $paymentProcess->getRedirectUrl() !!}', 'timeout', 5);
        </script>
    @elseif($order->payment_type == \App\Common\Enum\PaymentMethodEnum::VNPAY)
        <script>
            redirectToPaymentUrl('{!! $paymentProcess->getRedirectUrl() !!}', 'timeout', 5);
        </script>
    @endif
@endsection
