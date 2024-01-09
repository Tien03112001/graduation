<?php

namespace App\Http\Controllers\web;

use App\Common\Enum\OrderPaymentStatusEnum;
use App\Common\Enum\OrderStatusEnum;
use App\Common\Enum\PaymentMethodEnum;
use App\Common\FacebookConversion\FacebookConversionEvent;
use App\Common\SEO\SEOPage;
use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Jobs\FacebookConversionJob;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Repository\OrderRepositoryInterface;
use App\Repository\PaymentMethodRepositoryInterface;
use App\Repository\PaymentTransactionRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use App\Repository\VoucherRepositoryInterface;
use App\Utils\CartUtil;
use App\Utils\PaymentUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderController extends RestController
{
    protected $voucherRepository;
    protected $paymentMethodRepository;
    protected $paymentTransactionRepository;
    protected $productRepository;

    public function __construct(OrderRepositoryInterface $repository, VoucherRepositoryInterface $voucherRepository,
                                PaymentMethodRepositoryInterface $paymentMethodRepository,
                                PaymentTransactionRepositoryInterface $paymentTransactionRepository,
                                ProductRepositoryInterface $productRepository)
    {
        parent::__construct($repository);
        $this->voucherRepository = $voucherRepository;
        $this->productRepository = $productRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->paymentTransactionRepository = $paymentTransactionRepository;
    }


    public function create(Request $request)
    {
        $page = new SEOPage("Giao dịch");
        $request->validate([
            'customer_name' => 'required|max:255',
            'customer_phone' => 'required|digits:10',
            'customer_email' => 'required|email',
            'customer_address' => 'required|max:255',
            'province_id' => 'required|numeric',
            'district_id' => 'required|numeric',
            'ward_id' => 'required|numeric',
            'paymentbar' => 'required|max:255',
            'shipping_fee' => 'required|numeric',
            'request' => 'nullable',
        ], [
            'customer_name.required' => 'Tên không được để trống',
            'customer_phone.required' => 'Số điện thoại không được để trống',
            'customer_email.required' => 'Email không được để trống',
            'customer_address.required' => 'Địa chỉ không được để trống',
            'province_id.required' => 'Chưa chọn tỉnh/thành phố',
            'district_id.required' => 'Chưa chọn quận/huyện',
            'ward_id.required' => 'Chưa chọn xã/phường',
            'paymentbar.required' => 'Chưa chọn phương thức thanh toán',
            'customer_name.max' => 'Tên quá dài',
            'customer_phone.digits' => 'Số điện thoại không đúng định dạng',
        ]);

        $paymentMethod = $this->paymentMethodRepository->find([
            WhereClause::query('name', $request->paymentbar)
        ]);
        if (empty($paymentMethod) || !($paymentMethod instanceof PaymentMethod)) {
            return redirect()->back()->withErrors(['errors' => 'Không hỗ trợ phương thức thanh toán ' . $request->paymentbar]);
        }


        $items = [];
        if ($request->buyNow == null) {
            $cart = CartUtil::getInstance()->setShippingFee($request->shipping_fee);
            if ($cart->isEmpty()) {
                return redirect()->back()->withErrors(['errors' => 'Giỏ hàng rỗng']);
            }
            foreach ($cart->getItems() as $id => $item) {
                array_push($items, [
                    'product_id' => explode(" ", $id)[0],
                    'quantity' => $item['quantity'],
                    'product_name' => $item['item']['name'],
                    'variant_id' => $item['size'],
                    'price' => $item['unit_price'],
                    'amount' => $item['unit_price'] * $item['quantity'],
                ]);
            }
            $orderAttributes = [
                'code' => strtoupper(Str::random(8)),
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'customer_email' => $request->customer_email,
                'province_id' => $request->province_id,
                'district_id' => $request->district_id,
                'ward_id' => $request->ward_id,
                'payment_type' => $request->paymentbar,
                'amount' => $cart->getTotalAmount(),
                'shipping_fee' => $cart->getShippingFee(),
                'vat' => 0,
                'discount' => $request->discount_value||0,
                'total_amount' => $request->total_money - $request->discount_value + $request->shipping_fee,
                'date_at' => now()->toDateString(),
                'request' => $request->input('request'),
                'payment_status' => OrderPaymentStatusEnum::PENDING,
                'order_status' => OrderStatusEnum::PENDING,
                'relations' => [
                    'items' => $items
                ]
            ];

        } else {

            $product = $this->productRepository->findById($request->product_id);
            array_push($items, [
                'product_id' => $product->id,
                'quantity' => $request->input('quantity'),
                'product_name' => $request->input('product_name'),
                'variant_id' => $request->input('product_variant'),
                'price' => $product->sale_price,
                'amount' => $product->sale_price * $request->input('quantity')

            ]);

            $orderAttributes = [
                'code' => strtoupper(Str::random(8)),
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'customer_email' => $request->customer_email,
                'province_id' => $request->province_id,
                'district_id' => $request->district_id,
                'ward_id' => $request->ward_id,
                'payment_type' => $request->paymentbar,
                'amount' => (int)$product->sale_price * $request->input('quantity'),
                'shipping_fee' => (int)$request->shippingFeeDisplayN,
                'vat' => 0,
                'discount' => 0,
                'total_amount' => (int)$request->get_totalN,
                'date_at' => now()->toDateString(),
                'request' => $request->input('request'),
                'payment_status' => OrderPaymentStatusEnum::PENDING,
                'order_status' => OrderStatusEnum::PENDING,
                'relations' => [
                    'items' => $items
                ]
            ];
        }
        try {
            DB::beginTransaction();
            $order = $this->repository->create($orderAttributes);
            if ($order instanceof Order) {
                if (!$request->input('buyNow')) {
                    $voucher = $cart->getVoucher();
                    if (isset($voucher)) {
                        $order->vouchers()->attach($voucher['id']);
                    }
                }
            }
            DB::commit();
            CartUtil::getInstance()->flushCart();
            $paymentProcess = PaymentUtil::getInstance()->process($paymentMethod, $order);

            return view('theme.order.payment', compact('order', 'paymentProcess', 'paymentMethod','page'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->withErrors(['errors' => 'Hệ thống đang xảy ra lỗi']);
        }
    }


    public function complete($channel, Request $request)
    {
        $order = null;
        $transaction = null;
        $inputData = $request->all();
        if ($channel == 'vnpay') {
            $orderCode = $inputData['vnp_TxnRef'];
            $order = $this->repository->find([
                WhereClause::query('code', $orderCode),
                WhereClause::query('payment_type', PaymentMethodEnum::VNPAY)
            ], null, ['payment_method', 'transaction']);

            if (empty($order) || !($order instanceof Order)) {
                return response('Không tìm thấy đơn hàng', 500);
            }

            if (empty($order->payment_method) || !($order->payment_method instanceof Order)) {
                return response('Không tìm thấy đơn hàng', 500);
            }

            $transaction = $order->transaction;

            FacebookConversionJob::dispatch(FacebookConversionEvent::createPurchaseEvent($order, $request));

        } elseif ($channel == 'momo') {

        } elseif ($channel == 'paypal') {

        } else if ($channel == 'cod') {
            $orderCode = $inputData['code'];
            $order = $this->repository->find([
                WhereClause::query('code', $orderCode),
                WhereClause::query('payment_type', PaymentMethodEnum::COD)
            ], null, ['payment_method', 'transaction']);
            $transaction = $order->transaction;
            FacebookConversionJob::dispatch(FacebookConversionEvent::createPurchaseEvent($order, $request));
        }
        return view('theme.order.complete', compact('order', 'transaction','page'));
    }

}
