@extends('theme.components.layout')

@section('content')
    <p>Mã đơn hàng {{$order->code}} đang ở trạng thái {{$order->order_status}}</p>
    @isset($transaction)
        <p>Trạng thái thanh toán: {{$transaction->status}}</p>
        <p>{{$transaction->message}}</p>
    @endif
    <p>Hãy vui lòng truy cập để xem thông tin đơn hàng nhé</p>

@endsection

@section('scripts')
@endsection