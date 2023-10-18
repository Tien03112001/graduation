<!doctype html>
<html lang="vi">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.3.0/paper.css">

    <style>
        @media print {
            @page {
                size: 70mm 22mm;
            }
        }

        html body {
            margin: 0;
            font-size: 8px;
        }

        html body * {
            margin: 0;
        }

        div.page {
            max-width: 100%;
            text-align: center;
        }

        div.page .code-left {
            float: left;
            width: 50%;
        }

        div.page .code-right {
            float: right;
            width: 50%;
        }

        .barcode {
            max-width: 90%;
            max-height: 60px;
        }

        .product-code {
            float: left;
            width: 50%;
        }

        .product-price {
            float: right;
            width: 50%;
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
@for ($i = 0; $i < ($quantity%2==0?$quantity:$quantity+1)/2; $i++)
    <div class='page'>
        <div class="code-left">
            <p class="shop-name">{{$shopName}}</p>
            <svg class="barcode"
                 data-format="CODE128"
                 data-value="{{$ip->code}}"
                 data-margin="1"
                 data-displayValue="false"
                 data-height="65"
            >
            </svg>
            <div>
                <p class="product-code">{{$ip->code}}</p>
                <p class="product-price">Giá: <strong>{{number_format($ip->product->price_old)}}đ</strong></p>
            </div>
        </div>
        <div class="code-right">
            <p class="shop-name">{{$shopName}}</p>
            <svg class="barcode"
                 data-format="CODE128"
                 data-value="{{$ip->code}}"
                 data-margin="1"
                 data-displayValue="false"
                 data-height="65"
            >
            </svg>
            <div>
                <p class="product-code">{{$ip->code}}</p>
                <p class="product-price">Giá: <strong>{{number_format($ip->product->price_old)}}đ</strong></p>
            </div>
        </div>
    </div>
@endfor
<script type="text/javascript"
        src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
<script>
    JsBarcode(".barcode").init();
</script>
</body>
</html>
