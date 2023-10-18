<html>
<head>
    <title>In đơn hàng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.3.0/paper.css">
    <style>
        @page {
            size: A5
        }

        html body {
            margin: 50px;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
            text-align: right;
        }

        th, td {
            padding: 5px;
        }

        h1, th {
            text-align: center;
        }
    </style>
    <style type="text/css" media="print">
        div.page {
            page-break-after: always;
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
<div class="page">
    <h1>Đơn hàng của SpeXi</h1>
    <p>Mã đơn: <strong>{{$order->id}}</strong></p>
    <p>Tên khách hàng: <strong>{{$order->customer_name}}</strong></p>
    <p>SDT: <strong>{{$order->customer_phone}}</strong></p>
    <p>Địa chỉ: <strong>{{$order->customer_address}} {{$order->ward}} {{$order->district}} {{$order->province}}</strong>
    </p>
    <table>
        <thead>
        <tr>
            <th>Mã</th>
            <th>SL</th>
            <th>Đơn giá</th>
            <th>Thành tiền</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->details as $d)
            <tr>
                <td>{{$d->detail_code}}</td>
                <td>{{$d->quantity}}</td>
                <td>{{$d->unit_price}}</td>
                <td>{{$d->amount}}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan='3'>Thành tiền</td>
            <td>{{$order->amount}}</td>
        </tr>
        <tr>
            <td colspan='3'>Phí ship</td>
            <td>{{$order->ship_fee}}</td>
        </tr>
        <tr>
            <td colspan='3'>Tổng tiền</td>
            <td>{{$order->total_amount}}</td>
        </tr>
        <tr>
            <td colspan='3'>Phải thu CoD</td>
            <td>{{$order->cod_fee}}</td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>