<?php

namespace App\Http\Controllers\modules\shipments;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Models\InventoryProduct;
use App\Models\ProductVariant;
use App\Models\SaleOrder;
use App\Models\SaleOrderShipping;
use App\Models\ShippingService;
use App\Models\ShippingStore;
use App\Models\ShippingUnit;
use App\Utils\GiaoHangUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaleOrderShippingController extends Controller implements ApiController
{
    public function index(Request $request)
    {
    }

    public function store(Request $request)
    {
        $orderIdsStr = $request->order_ids;

        if (empty($orderIdsStr)) {
            return $this->error('Không có đơn hàng nào');
        }

        $orderIds = preg_split('/,/', $orderIdsStr);

        $orders = SaleOrder::whereIn('id', $orderIds)->with('details', 'shipping.unit')->get();
        if (empty($orders)) {
            return $this->error('Đơn hàng không tồn tại');
        }

        $deliverId = $request->input('deliver_id');
        $unit = ShippingUnit::find($deliverId);
        if (empty($unit)) {
            return $this->error('Không hỗ trợ ĐVVC này');
        }

        $storeId = $request->input('store_id');
        $store = null;
        if (isset($storeId)) {
            $store = ShippingStore::where('unit_id', $deliverId)->find($storeId);
        }
        if (empty($store)) {
            $store = ShippingStore::where('unit_id', $deliverId)->where('is_often', 1)->first();
        }

        $serviceId = $request->input('service_id');
        $service = null;
        if (isset($serviceId)) {
            $service = ShippingService::where('unit_id', $deliverId)->find($serviceId);
        }
        if (empty($service)) {
            $service = ShippingService::where('unit_id', $deliverId)->where('is_often', 1)->first();
        }

        $successOrders = [];
        foreach ($orders as $order) {
            foreach($order->details as $d) {
                $variant = ProductVariant::whereProductId($d->product_id)->whereId($d->variant_id)->first();
                if($variant->weight <= 0 && $unit->name != "Tự giao") {
                    return $this->errorClient('Sản phẩm '.$d->product_code.' chưa cấu hình khối lượng');
                }
            }
        }
        foreach ($orders as $order) {
            if (!($order instanceof SaleOrder)) {
                continue;
            }
            if ($order->shipping) {
                continue;
            }
            try {
                DB::beginTransaction();
                $giaoHangUtil = new GiaoHangUtil($unit);
                $dataResponse = $giaoHangUtil->createOrder($order, $store, $service);
                $shipping = new SaleOrderShipping();
                $shipping->order_id = $order->id;
                $shipping->store_id = $store->id;
                $shipping->service_id = $service->id;
                $shipping->deliver_id = $deliverId;
                if($unit->name == "Tự giao") {
                    $shipping->status = 'Đã điều phối giao hàng/Đang giao hàng';
                } else {
                    $shipping->status = 'Đã tiếp nhận';
                }
                $shipping->status_id = 2;
                $shipping->note = null;
                $shipping->code = $dataResponse->order_code;
                $shipping->total_fee = $dataResponse->total_fee;
                $shipping->expected_delivery_time = $dataResponse->expected_delivery_time;
                $shipping->save();
                $order->status = SaleOrder::$TRANG_THAI_DA_CHUAN_BI_HANG;
                $order->save();
                // // Trừ sản phẩm trong kho
                foreach ($order->details as $d) {
                    $inventory = InventoryProduct::whereProductId($d->product_id)->whereVariantId($d->variant_id)->first();
                    $inventory->used_quantity = $inventory->used_quantity + $d->quantity;
                    $inventory->save();
                }
                DB::commit();
                $order->load(['shipping.unit']);
                array_push($successOrders, $order);
            } catch (\Exception $e) {
                Log::error($e);
                DB::rollBack();
            }
        }
        return $this->success(['orders' => $successOrders, 'exporting_note' => []]);

    }

    public function show($id)
    {
        $model = SaleOrderShipping::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }

        $ghu = new GiaoHangUtil($model);
        $info = $ghu->getOrder($model);
        return $this->success($info);
    }

    public function update(Request $request, $id)
    {
        $status = $request->input('status');
        $model = SaleOrderShipping::with('order')->find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }

        if ($model->deliver_id == 0 && $status == 'Đã đối soát') {
            $model->status = 'Đã đối soát';
            $model->status_id = 6;
            $model->order->stataus = 'Đã giao';
            try {
                DB::beginTransaction();
                $model->save();
                $model->order->save();
                DB::commit();
                return $this->success($model);
            } catch (\Exception $e) {
                Log::error($e);
                DB::rollBack();
                return $this->error($e->getMessage());
            }
        }

    }

    public function destroy($id)
    {
        $model = SaleOrderShipping::with('order')->find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        try {
            DB::beginTransaction();
            $ghu = new GiaoHangUtil($model);
            $ghu->cancelOrder($model);
            $model->delete();
            $model->order->status = 'Chuẩn bị hàng';
            $model->order->save();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function recreate($id)
    {
        $model = SaleOrderShipping::with(['order', 'store', 'unit', 'service'])->find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        try {
            DB::beginTransaction();
            $ghu = new GiaoHangUtil($model);
            $ghu->cancelOrder($model);
            $giaoHangUtil = new GiaoHangUtil($model->unit);
            $dataResponse = $giaoHangUtil->createOrder($model->order, $model->store, $model->service);
            $model->status = 'Đã tiếp nhận';
            $model->status_id = 2;
            $model->note = null;
            $model->code = $dataResponse->order_code;
            $model->total_fee = $dataResponse->total_fee;
            $model->expected_delivery_time = $dataResponse->expected_delivery_time;
            $model->save();
            $model->order->status = SaleOrder::$TRANG_THAI_DANG_GIAO;
            $model->order->save();
            DB::commit();
            $model->load(['unit']);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function printBill($id)
    {
        $model = SaleOrderShipping::with('order')->find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        try {
            $ghu = new GiaoHangUtil($model);
            return $this->success(['link' => $ghu->printOrders([$model->order])]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }


}
