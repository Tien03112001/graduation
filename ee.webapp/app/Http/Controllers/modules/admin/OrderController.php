<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\OrderRepositoryInterface;
// use App\Repository\OrderItemRepositoryInterface;
// use App\Repository\OrderDetailRepositoryInterface;
// use App\Repository\SaleOrderRepositoryInterface;
// use App\Repository\SaleOrderDetailRepositoryInterface;
// use App\Repository\InventoryProductRepositoryInterface;
// use App\Repository\ProductRepositoryInterface;
// use App\Repository\PaymentTypeRepositoryInterface;
// use App\Repository\VoucherRepositoryInterface;
// use App\Repository\SaleCustomerRepositoryInterface;
// use App\Models\SaleOrder;
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
    protected $orderItemRepository;
    // protected $detailRepository;
    // protected $saleRepository;
    // protected $saleDetailRepository;
    // protected $inventoryRepository;
    // protected $paymentRepository;
    // protected $promotionRepository;
    // protected $voucherRepository;
    // protected $customerRepository;

    public function __construct(
        OrderRepositoryInterface $repository,
        // ProductRepositoryInterface $productRepository,
        // OrderItemRepositoryInterface $orderItemRepository,
        // InventoryProductRepositoryInterface $inventoryRepository,
        // SaleOrderRepositoryInterface $saleRepository,
        // SaleOrderDetailRepositoryInterface $saleDetailRepository,
        // VoucherRepositoryInterface $voucherRepository,
        // SaleCustomerRepositoryInterface $customerRepository,
        // PaymentTypeRepositoryInterface $paymentRepository
    ) {
        parent::__construct($repository);
        // $this->productRepository = $productRepository;
        // $this->detailRepository = $detailRepository;
        // $this->inventoryRepository = $inventoryRepository;
        // $this->saleRepository = $saleRepository;
        // $this->saleDetailRepository = $saleDetailRepository;
        // $this->paymentRepository = $paymentRepository;
        // $this->voucherRepository = $voucherRepository;
        // $this->customerRepository = $customerRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $orderBy = $request->input('orderBy', 'updated_at:desc');
        $with = ['order_details', 'province', 'district', 'ward'];
        $withCount = [];
        if ($request->has('search') && Str::length($request->search) > 0) {
            array_push($clauses, WhereClause::queryLike('customer_name', $request->search));
        }
        if ($request->has('search') && Str::length($request->search) == 0) {
            $data = '';
            return $this->success($data);
        }
        if ($request->has('order_id')) {
            array_push($clauses, WhereClause::queryLike('id', $request->order_id));
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

    // public function store(Request $request)
    // {
    //     return response()->json(['message' => 'Not support', 'status' => 0], 400);
    // }

    // public function show($id)
    // {
    //     return response()->json(['message' => 'Not support', 'status' => 0], 400);
    // }

    // Xác nhận
    public function update(Request $request, $id)
    {
        $order = $this->repository->findById($id, ['order_details']);

        if (empty($order)) {
            return $this->error('Đơn hàng không tồn tại');
        }

        $validator = Validator::make($request->all(), [
            'customer_name' => 'required',
            'customer_phone' => 'required',
            'customer_address' => 'required',
            'total_amount' => 'required',
            'payment_type' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        $attributes['code'] = $order->code;
        $attributes['customer_name'] = $request->input('customer_name');
        $attributes['customer_phone'] = $request->input('customer_phone');
        $attributes['customer_address'] = $request->input('customer_address');
        // $attributes['ship_fee'] = $request->input('ship_fee', 0);
        $attributes['payment_type'] = $request->input('payment_type');
        $attributes['note'] = $request->input('note');
        $attributes['order_status'] = $request->input('order_status');

        if($request->request == 'null') {
            $attributes['customer_request'] = null;
        }
        if($request->banking_sms == 'null') {
            $attributes['banking_sms'] = null;
        }
        if($request->note == 'null') {
            $attributes['note'] = null;
        }

        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, $attributes);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $ex) {
            Log::error($ex);
            DB::rollBack();
            return $this->error($ex->getMessage());
        }
    }

    // public function destroy($id)
    // {
    //     return response()->json(['message' => 'Not support', 'status' => 0], 400);
    // }

    // // Hủy đơn
    // public function cancel($id, Request $request)
    // {
    //     $model = Order::with('order_details')->find($id);
    //     if (empty($model)) {
    //         return $this->error('Đối tượng không tồn tại');
    //     }
    //     try {
    //         DB::beginTransaction();
    //         $model->order_status = "Hủy đơn";
    //         $model->note = $request->input('note');
    //         if($request->note == 'null') {
    //             $attributes['note'] = null;
    //         }
    //         $model->save();
    //         DB::commit();
    //         return $this->success($model);
    //     } catch (\Exception $e) {
    //         Log::error($e);
    //         DB::rollBack();
    //         return $this->error($e->getMessage());
    //     }
    // }
}
