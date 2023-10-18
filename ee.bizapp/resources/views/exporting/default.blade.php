<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.3.0/paper.css">
    <style>
        p {
            margin-block-start: 5px;
            margin-block-end: 5px;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
        }

        .col-1 {
            width: 10%;
        }

        .col-2 {
            width: 20%;
        }

        .col-3 {
            width: 30%;
        }

        .col-4 {
            width: 40%;
        }

        .col-5 {
            width: 50%;
        }

        .col-6 {
            width: 60%;
        }

        .col-7 {
            width: 70%;
        }

        .col-8 {
            width: 80%;
        }

        html, body {
            width: 100%;
            margin: 0;
            font-size: 10px;
        }

        table {
            border-collapse: collapse;
        }

        .page {
            width: 98%;
            margin: 0 auto;
        }

        .page table {
            width: 100%;
        }

        .page table, .page th, .page tr, .page td {
            border: none;
            padding: 10px;
        }

        .product-list table {
            width: 100%;
        }

        .product-list table, .product-list tr, .product-list th, .product-list td {
            padding: 5px;
            border: 1px solid black;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        @page {
            size: A5;

        }
    </style>
</head>
<body class="A5">
<div class="page sheet padding-10mm">
    <table>
        <tbody>
        <tr>
            <td class="col-6 text-center">
                <p>
                    <img alt="Company Logo" src="{{$settings['logo_path']??asset('images/logo.png')}}" width="100">
                </p>
                <p>{{$settings['master_name']??'Chưa cấu hình'}}</p>
            </td>
            <td class="col-6 text-center">
                <h2 class="text-uppercase">Phiếu xuất kho</h2>
                <p class="text-right"><strong>Ngày:</strong> {{now()->toDateTimeString()}}.</p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p><strong>Tên phiếu:</strong> {{$model->name}}.</p>
                <p><strong>Người tạo:</strong> {{$model->creator_name}}.</p>
            </td>
        </tr>
        <tr>
            <td class="product-list" colspan="2">
                <table>
                    <thead>
                    <tr>
                        <th class="text-right">#</th>
                        <th class="text-center">Sản phẩm</th>
                        <th class="text-center">Size</th>
                        <th class="text-center">SL</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($model->details as $index=>$d)
                        <tr>
                            <td class="col-1 text-right">{{$index+1}}</td>
                            <td class="col-6">Mã {{$d->product_code}}</td>
                            <td class="col-2 text-center">{{$d->size}}</td>
                            <td class="col-3 text-right">{{$d->quantity}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                <p><strong class="text-uppercase">Người lập phiếu</strong></p>
                <p>Ký nhận</p>
            </td>
            <td class="text-center">
                <strong class="text-uppercase">Người xuất kho</strong>
                <p>Ký nhận</p>
            </td>
        </tr>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
</body>
</html>