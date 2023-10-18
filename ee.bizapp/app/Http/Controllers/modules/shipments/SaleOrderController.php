<?php

namespace App\Http\Controllers\modules\shipments;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\InventoryProduct;
use App\Models\SaleOrder;
use App\Models\SaleOrderShipping;
use App\Models\SaleOrderStatus;
use App\Utils\GiaoHangUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Repository\SaleOrderRepositoryInterface;

class SaleOrderController extends RestController
{
    public function __construct(SaleOrderRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $orderBy = $request->input('orderBy', 'updated_at:desc');
        $with = ['details.variant', 'shipping.unit', 'seller'];
        $withCount = [];

        array_push($clauses, WhereClause::queryDiff('status', 'Lưu nháp'));

        if ($request->has('customer_name') && Str::length($request->customer_name) > 0) {
            array_push($clauses, WhereClause::queryLike('customer_name', $request->customer_name));
        }
        if ($request->has('customer_name') && Str::length($request->customer_name) == 0) {
            $data = '';
            return $this->success($data);
        }

        if ($request->has('shipping_status')) {
            $shipping_status = $request->shipping_status;
            array_push($clauses, WhereClause::queryRelationHas('shipping', function ($q) use ($shipping_status) {
                $q->where('status', $shipping_status);
            }));
        }

        if ($request->has('shipping_code')) {
            $shipping_code = $request->shipping_code;
            array_push($clauses, WhereClause::queryRelationHas('shipping', function ($q) use ($shipping_code) {
                $q->where('code', $shipping_code);
            }));
        }

        if ($request->has('customer_phone')  && Str::length($request->customer_phone) > 0) {
            $phone = trim($request->customer_phone);
            array_push($clauses, WhereClause::query('customer_phone', $phone));
        }
        if ($request->has('customer_phone') && Str::length($request->customer_phone) == 0) {
            $data = '';
            return $this->success($data);
        }
        if ($request->has('status')) {
            array_push($clauses, WhereClause::query('status', $request->status));
        }

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->repository->get($clauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $data = SaleOrder::with('sale_order_details.product', 'customer')->find($id);
        $data->sales_note = $request->input('sales_note');
        $data->status = $request->input('status');
        $data->shipping_status = $request->input('shipping_status');
        $data->payment_status = $request->input('payment_status');
        $data->shipping_date = $request->input('shipping_date');
        $data->shipping_feedback = $request->input('shipping_feedback');
        if ($data->shipping_status == 2 || $data->status == 3) {
            $data->bol_id = null;
        }
        $data->save();
        return $this->success($data);
    }

    public function destroy($id)
    {
    }

    public function return($id)
    {
        $model = SaleOrder::with('shipping', 'details')->find($id);
        if (empty($model)) {
            return $this->errorClient('Đối tượng không tồn tại');
        }
        try {
            DB::beginTransaction();
            SaleOrder::setReturn($model);
            $model->save();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function receiveRefund($id)
    {
        $model = SaleOrder::with('shipping', 'details')->find($id);
        if (empty($model)) {
            return $this->errorClient('Đối tượng không tồn tại');
        }
        try {
            DB::beginTransaction();
            SaleOrder::setReceivedRefund($model);
            //Cập nhật kho
            foreach ($model->details as $d) {
                $inv = (new InventoryProduct())->newQuery()
                        ->where('product_id', $d->product_id)
                        ->where('variant_id', $d->variant_id)
                        ->first();
                if (empty($inv)) {
                    return $this->errorClient("Không tồn tại $d->product_id trong kho");
                } else {
                    $inv->used_quantity -= $d->quantity;
                    $inv->save();
                }
            }
            $model->save();
            DB::commit();
            $model->load(['details', 'shipping.unit', 'seller']);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function done($id)
    {
        $model = SaleOrder::with('shipping')->find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        try {
            DB::beginTransaction();
            SaleOrder::setDone($model);
            $model->save();
            DB::commit();
            $model->load(['details', 'shipping.unit', 'seller']);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function refund($id, Request $request)
    {
        $model = SaleOrder::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        $model->refund = $request->input('refund');
        $model->note = $request->input('note');
        $model->status = SaleOrder::$TRANG_THAI_HOAN_TIEN;
        try {
            $model->save();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    public function cancel($id, Request $request)
    {
        $model = SaleOrder::with('details', 'shipping')->find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        try {
            DB::beginTransaction();
            SaleOrder::setCanceled($model);
            if ($model->shipping) {
                $ghUtil = new GiaoHangUtil($model);
                $response = $ghUtil->cancelOrder($model->shipping);
                if ($response) {
                    $model->shipping->save();
                } else {
                    throw new \Exception('Không thể hủy đơn hàng');
                }
            }

            //cap nhat kho
            foreach ($model->details as $d) {
                $inv = (new InventoryProduct())->newQuery()
                    ->where('code', $d->detail_code)
                    ->first();
                if (empty($inv)) {
                    throw new \Exception("Không tồn tại $d->detail_code trong kho");
                } else {
                    $inv->quantity += $d->quantity;
                    $inv->save();
                }
            }

            $model->note = $request->input('note');
            $model->save();
            DB::commit();
            $model->load(['details', 'shipping.unit', 'seller']);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function reUpdate($id, Request $request)
    {
        $model = SaleOrder::with('details', 'shipping')->find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }

        try {
            DB::beginTransaction();
            SaleOrder::setReUpdated($model);
            $model->save();
            DB::commit();
            $model->load(['details', 'shipping.unit', 'seller']);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function printShippingBill(Request $request)
    {
        $orderIdsStr = $request->order_ids;
        if (empty($orderIdsStr)) {
            return $this->error('Không có đơn hàng nào');
        }
        $orderIds = preg_split('/,/', $orderIdsStr);
        $models = SaleOrder::with('shipping.unit', 'details')
            ->whereIn('id', $orderIds)
            ->get();
        if (empty($models)) {
            return $this->error('Đối tượng không tồn tại');
        }
        try {
            DB::beginTransaction();
            $ghu = new GiaoHangUtil($models[0]);
            $link = $ghu->printOrders($models);
            SaleOrderShipping::whereIn('order_id', $orderIds)
                ->update(['is_printed' => 1]);
            DB::commit();
            return $this->success(compact('link'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $models);
        }
    }

    public function noteShip($id, Request $request)
    {
        $model = SaleOrder::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        $model->note_ship = $request->input('note_ship');
        try {
            $model->save();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }
}
