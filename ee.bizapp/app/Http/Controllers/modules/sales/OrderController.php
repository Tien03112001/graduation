<?php

namespace App\Http\Controllers\modules\sales;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\OrderRepositoryInterface;
use App\Repository\OrderDetailRepositoryInterface;
use App\Repository\SaleOrderRepositoryInterface;
use App\Repository\SaleOrderDetailRepositoryInterface;
use App\Repository\InventoryProductRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use App\Repository\PaymentTypeRepositoryInterface;
use App\Repository\VoucherRepositoryInterface;
use App\Repository\SaleCustomerRepositoryInterface;
use App\Models\SaleOrder;
use App\Models\Order;
use App\Jobs\ChangeStatus;
use App\Utils\AuthUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Utils\FileStorageUtil;
use Illuminate\Support\Str;

class OrderController  extends RestController
{
    protected $productRepository;
    protected $detailRepository;
    protected $saleRepository;
    protected $saleDetailRepository;
    protected $inventoryRepository;
    protected $paymentRepository;
    protected $promotionRepository;
    protected $voucherRepository;
    protected $customerRepository;

    public function __construct(
        OrderRepositoryInterface $repository,
        ProductRepositoryInterface $productRepository,
        OrderDetailRepositoryInterface $detailRepository,
        InventoryProductRepositoryInterface $inventoryRepository,
        SaleOrderRepositoryInterface $saleRepository,
        SaleOrderDetailRepositoryInterface $saleDetailRepository,
        VoucherRepositoryInterface $voucherRepository,
        SaleCustomerRepositoryInterface $customerRepository,
        PaymentTypeRepositoryInterface $paymentRepository
    ) {
        parent::__construct($repository);
        $this->productRepository = $productRepository;
        $this->detailRepository = $detailRepository;
        $this->inventoryRepository = $inventoryRepository;
        $this->saleRepository = $saleRepository;
        $this->saleDetailRepository = $saleDetailRepository;
        $this->paymentRepository = $paymentRepository;
        $this->voucherRepository = $voucherRepository;
        $this->customerRepository = $customerRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $orderBy = $request->input('orderBy', 'updated_at:desc');
        $with = ['order_details.variant', 'order_details.product.variants', 'province', 'district', 'ward'];
        $withCount = [];
        if ($request->has('search') && Str::length($request->search) > 0) {
            array_push($clauses, WhereClause::queryLike('customer_name', $request->search));
        }
        if ($request->has('search') && Str::length($request->search) == 0) {
            $data = '';
            return $this->success($data);
        }
        if ($request->has('customer_phone')) {
            array_push($clauses, WhereClause::queryLike('customer_phone', $request->customer_phone));
        }
        if ($request->has('created_date')) {
            array_push($clauses, WhereClause::queryDate('created_at', $request->created_date));
        }

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->repository->get($clauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        return response()->json(['message' => 'Not support', 'status' => 0], 400);
    }

    public function show($id)
    {
        return response()->json(['message' => 'Not support', 'status' => 0], 400);
    }

    // Xác nhận
    public function update(Request $request, $id)
    {
        $sellerId = AuthUtil::getInstance()->getModel()->id;
        $order = $this->repository->findById($id, ['order_details']);

        if (empty($order)) {
            return $this->error('Đơn hàng không tồn tại');
        }

        $validator = Validator::make($request->all(), [
            'channel' => 'required',
            'customer_name' => 'required',
            'customer_phone' => 'required',
            'customer_address' => 'required',
            'province' => 'required',
            'district' => 'required',
            'ward' => 'required',
            'total_amount' => 'required',
            'cod_fee' => 'required',
            'payment_type' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }

        $prefix = 'SPX_';
        $code = $prefix . rand(10000, 100000);
        while (SaleOrder::whereCode($code)->exists()) {
            $code = $prefix . rand(10000, 100000);
        }
        $attributes['seller_id'] = $sellerId;
        $attributes['code'] = $code;
        $attributes['channel'] = $request->input('channel');
        $attributes['customer_name'] = $request->input('customer_name');
        $attributes['customer_phone'] = $request->input('customer_phone');
        $attributes['customer_address'] = $request->input('customer_address');
        $attributes['province'] = $request->input('province');
        $attributes['district'] = $request->input('district');
        $attributes['ward'] = $request->input('ward');
        $attributes['customer_request'] = $request->input('request');
        $attributes['ship_fee'] = $request->input('ship_fee', 0);
        $attributes['cod_fee'] = $request->input('cod_fee', 0);
        $attributes['payment_type'] = $request->input('payment_type');
        $attributes['banking_sms'] = $request->input('banking_sms');
        $attributes['note'] = $request->input('note');
        $attributes['banking_img'] = '';
        $createdImages = [];
        if($request->request == 'null') {
            $attributes['customer_request'] = null;
        }
        if($request->banking_sms == 'null') {
            $attributes['banking_sms'] = null;
        }
        if($request->note == 'null') {
            $attributes['note'] = null;
        }
        if ($request->file('banking_img') != null) {
            $banking = FileStorageUtil::getInstance()->putFile('banking_img', $request->file('banking_img'));
            array_push($createdImages, $banking);
            $attributes['banking_img'] = $banking;
        }

        // Lặp sản phẩm
        $products = json_decode($request->product, true);
        if ($products == null) {
            return $this->errorClient('Chưa chọn sản phẩm nào');
        }
        try {
            DB::beginTransaction();
            // Kiểm tra người dùng
            $customer = $this->customerRepository->find([WhereClause::query('phone', $request->input('customer_phone'))]);
            if ($customer == null) {
                $attributeCustomers['name'] = $request->input('customer_name');
                $attributeCustomers['phone'] = $request->input('customer_phone');
                $attributeCustomers['address'] = $request->input('customer_address');
                $attributeCustomers['province'] = $request->input('province');
                $attributeCustomers['district'] = $request->input('district');
                $attributeCustomers['ward'] = $request->input('ward');
                $this->customerRepository->create($attributeCustomers);
            }
            //Kiểm tra
            foreach ($products as $p) {
                //Kiểm tra giá trị số
                if ($p['quantity'] <= 0 || $p['quantity'] == null) {
                    return $this->errorClient('Số lượng sản phẩm ' . $p['product']['code'] . ' không hợp lệ');
                }
                if ($p['unit_price'] <= 0 || $p['unit_price'] == null) {
                    return $this->errorClient('Giá sản phẩm ' . $p['product']['code'] . ' không hợp lệ');
                }
                if ($p['variant_id'] == 0) {
                    return $this->errorClient('Sản phẩm ' . $p['product']['code'] . ' chưa chọn biến thể');
                }
                // Tìm số biến thể trong kho
                $inventory = $this->inventoryRepository->find([WhereClause::query('product_id', $p['product']['id']), WhereClause::query('variant_id', $p['variant_id'])]);

                if ($inventory->quantity == 0) {
                    return $this->errorClient('Sản phẩm ' . $p['product']['code'] . ' không đủ hàng');
                }
            }
            if($request->has('voucher')) {
                $voucher = $this->voucherRepository->findById($request->voucher);
                if (empty($voucher)) {
                    return $this->error('Mã giảm giá không đúng');
                }
                $this->voucherRepository->update($voucher->id, ['quantity' => $voucher->quantity - 1]);
                $attributes['voucher_id'] = $request->voucher;
            }
            $attributes['amount'] = $request->amount;
            $attributes['total_amount'] = $request->amount + $attributes['ship_fee'];
            $attributes['status'] = SaleOrder::$TRANG_THAI_XAC_THUC;
            $sale_order = $this->saleRepository->create($attributes);

            // Tạo chi tiết đơn hàng
            foreach ($products as $p) {
                $attributeDetails['order_id'] =  $sale_order->id;
                $attributeDetails['detail_code'] =  $sale_order->id;
                $attributeDetails['size'] = $request->input('size', 0);
                $attributeDetails['product_id'] = $p['product']['id'];
                $attributeDetails['product_code'] = $p['product']['code'];
                $attributeDetails['variant_id'] = $p['variant_id'];
                $attributeDetails['quantity'] = $p['quantity'];
                $attributeDetails['unit_price'] = $p['unit_price'];
                $attributeDetails['amount'] = $attributeDetails['quantity'] * $attributeDetails['unit_price'];
                $this->saleDetailRepository->create($attributeDetails);
            }

            // Xóa đơn hàng trên website
            $this->detailRepository->bulkDelete([WhereClause::query('order_id', $id)]);
            $this->repository->delete($id);
            DB::commit();
            ChangeStatus::dispatch($sale_order->id)->delay(now()->addMinutes(60))->afterCommit();
            return $this->success($sale_order);
        } catch (\Exception $ex) {
            Log::error($ex);
            DB::rollBack();
            if ($request->file('banking_img') != null) {
                FileStorageUtil::getInstance()->deleteFile($attributes['banking_img']);
            }
            return $this->error($ex->getMessage());
        }
    }

    public function destroy($id)
    {
        return response()->json(['message' => 'Not support', 'status' => 0], 400);
    }

    // Hủy đơn
    public function cancel($id, Request $request)
    {
        $model = Order::with('order_details')->find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        try {
            DB::beginTransaction();
            $model->order_status = "Hủy đơn";
            $model->note = $request->input('note');
            if($request->note == 'null') {
                $attributes['note'] = null;
            }
            $model->save();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}
