<?php

namespace App\Http\Controllers\modules\sales;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\SaleOrder;
use App\Models\SaleOrderShipping;
use App\Utils\Logistics\GiaoHangNhanhUtil;
use App\Utils\Logistics\GiaoHangTietKiemUtil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SaleOrderShippingController extends Controller implements ApiController
{
    public function index(Request $request)
    {
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'deliver_id' => 'required',
            'code' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        try {
            DB::beginTransaction();
            $model = SaleOrderShipping::create([
                'deliver_id' => $request->input('deliver_id'),
                'code' => $request->input('code'),
                'status' => 'ready_to_pick'
            ]);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function show($id)
    {
        // TODO: Implement show() method.
        $model = SaleOrderShipping::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        return $this->success($model);
    }

    public function update(Request $request, $id)
    {
        // TODO: Implement update() method.
        $model = SaleOrderShipping::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        try {
            DB::beginTransaction();
            $model->code = $request->input('code');
            $model->save();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
        $model = SaleOrderShipping::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        try {
            DB::beginTransaction();
            if ($model->deliver_id == 1) {
                GiaoHangNhanhUtil::cancelOrder($model);
            }
            $model->delete();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function sync($id)
    {
        // TODO: Implement destroy() method.
        $model = SaleOrderShipping::with('order')->find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        try {
            DB::beginTransaction();
            if ($model->shipping->deliver_id == 1) {
                self::syncGhnOrder($model);
            }
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    /**
     * @param SaleOrderShipping $model
     * @throws \Exception
     */
    public static function syncGhnOrder(SaleOrderShipping $model)
    {
        $ghn = GiaoHangNhanhUtil::getOrder($model->code);
        $order = $model->order;
        $model->status = $ghn->status;
        $model->updated_at = Carbon::parse($model->updated_date);
        $model->updated_at = Carbon::parse($model->updated_date);
        if ($model->status == 'delivered') {
            $order->status = SaleOrder::$TRANG_THAI_DA_GIAO;
        }
        if (Str::startsWith('return', $model->status)) {
            $order->status = SaleOrder::$TRANG_THAI_HOAN_VE;
        }
        if ($order->shipping->status == 'returned') {
            $order->status = SaleOrder::$TRANG_THAI_DA_NHAN_HOAN;
        }
        if ($order->shipping->status == 'damage') {
            $order->status = SaleOrder::$TRANG_THAI_MAT_HANG;
        }
        if ($order->shipping->status == 'lost') {
            $order->status = SaleOrder::$TRANG_THAI_MAT_HANG;
        }
        if ($order->shipping->status == 'cancel') {
            $order->status = SaleOrder::$TRANG_THAI_HUY_DON;
        }
        $model->save();
        $order->save();
    }

    public function info($id)
    {
        $model = SaleOrderShipping::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        try {
            if ($model->deliver_id == 1) {
                $info = GiaoHangTietKiemUtil::getStatus($model->code);
            } else {
                $info = $model->toArray();
            }
            return $this->success($info);
        } catch (\Exception $e) {
            return $this->error($e);
        }
    }
}
