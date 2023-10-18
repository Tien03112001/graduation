<?php

namespace App\Http\Controllers\modules\manager;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\InventoryProduct;
use App\Models\SaleOrder;
use App\Models\SaleOrderDetail;
use App\Utils\AuthUtil;
use App\Utils\GiaoHangNhanhUtil;
use App\Utils\InventoryUtil;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SaleOrderController extends Controller implements ApiController
{
    private function splitDetailText($text)
    {
        $details = [];
        $lines = explode("\n", $text);
        foreach ($lines as $numRow => $line) {
            $values = preg_split('/[^\-\w]+/', $line, -1, PREG_SPLIT_NO_EMPTY);

            if (count($values) < 3) {
                throw new \Exception(sprintf('Dòng thứ %d sai định dạng', $numRow));
            }

            if (!is_numeric($values[2])) {
                throw new \Exception(sprintf('Dòng thứ %d, Đơn giá sai định dạng', $numRow));
            }
            $quantity = 1;
            if (count($values) > 3) {
                if (!is_numeric($values[3])) {
                    throw new \Exception(sprintf('Dòng thứ %d, SL sai định dạng', $numRow));
                } else {
                    $quantity = intval($values[3]);
                }
            }

            $productCode = $values[0];
            $size = Str::upper($values[1]);
            $inventoryProduct = InventoryProduct::where('code', $productCode . '-' . $size)->first();
            if (empty($inventoryProduct)) {
                throw new \Exception(sprintf('Dòng %d - Mã hàng %s-%s chưa có hàng', $numRow + 1, $productCode, $size));
            }
            if ($inventoryProduct->quantity <= 0) {
                throw new \Exception(sprintf('Dòng %d - Mã hàng %s-%s hết hàng', $numRow + 1, $productCode, $size));
            }

            if ($inventoryProduct->quantity < $quantity) {
                throw new \Exception(sprintf('Dòng %d - Mã hàng %s-%s thiếu hàng', $numRow + 1, $productCode, $size));
            }
            $detail = new SaleOrderDetail();
            $detail->detail_code = $productCode . "-" . $size;
            $detail->product_id = $inventoryProduct->product_id;
            $detail->product_code = $productCode;
            $detail->size = $size;
            $detail->quantity = $quantity;
            $detail->unit_price = intval($values[2]);
            $detail->amount = $detail->quantity * $detail->unit_price;
            array_push($details, $detail);
        }
        return $details;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit');
        $status = $request->input('status');
        $code = $request->input('code');
        $customer_phone = $request->input('customer_phone');
        $channel = $request->input('channel');
        $sellerName = $request->input('seller_name');
        $createdDate = $request->input('created_date');
        $isShipping = $request->input('is_shipping');

        $search = $request->input('search');
        $sql = (new SaleOrder())
            ->when(isset($channel), function ($q) use ($channel) {
                return $q->where('channel', $channel);
            })
            ->when(isset($isShipping), function ($q) use ($isShipping) {
                if ($isShipping == 1) {
                    return $q->has('shipping');
                } else {
                    return $q->doesntHave('shipping');
                }
            })
            ->when(isset($sellerName), function ($q) use ($sellerName) {
                return $q->whereHas('seller', function (Builder $q) use ($sellerName) {
                    $q->where('name', 'like', $sellerName);
                });
            })
            ->when(isset($customer_phone), function ($q) use ($customer_phone) {
                return $q->where('customer_phone', 'like', '%' . $customer_phone . '%');
            })
            ->when(isset($status), function ($q) use ($status) {
                return $q->where('status', '=', $status);
            })
            ->when(isset($search), function ($q) use ($search) {
                return $q->where('note', 'like', '%' . $search . '%')
                    ->orWhere('customer_name', 'like', '%' . $search . '%')
                    ->orWhere('customer_address', 'like', '%' . $search . '%')
                    ->orWhere('customer_request', 'like', '%' . $search . '%')
                    ->orWhere('note', 'like', '%' . $search . '%');
            })
            ->when(isset($code), function ($q) use ($code) {
                return $q->where('code', 'like', '%' . $code . '%');
            })
            ->when(isset($createdDate), function (Builder $q) use ($createdDate) {
                return $q->whereDate('created_at', $createdDate);
            })
            ->with('details', 'shipping', 'seller')
            ->orderBy('id', 'DESC');
        if ($limit) {
            $data = $sql->paginate($limit);
        } else {
            $data = $sql->get();
        }
        return $this->success($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sellerId = AuthUtil::getInstance()->getModel()->id;
        $validator = Validator::make($request->all(), [
            'channel' => 'required',
            'customer_phone' => 'required',
            'customer_name' => 'required',
            'customer_address' => 'required',
            'province' => 'required',
            'district' => 'required',
            'ward' => 'required',
            'ship_fee' => 'required|numeric',
            'details_text' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }

        $order = new SaleOrder();
        $code = 'HH_' . rand(10000, 100000);
        while (SaleOrder::whereCode($code)->exists()) {
            $code = 'HH_' . rand(10000, 100000);
        }
        $order->code = $code;
        $order->seller_id = $sellerId;
        $order->channel = $request->input('channel');


        //Thong tin khach hang
        $order->customer_name = $request->input('customer_name');
        $order->customer_phone = $request->input('customer_phone');
        if (strlen($order->customer_phone) != 10) {
            return $this->error('SĐT khách hàng không đúng');
        }
        $order->customer_address = $request->input('customer_address');
        $order->province = $request->input('province');
        $order->district = $request->input('district');
        $order->ward = $request->input('ward');
        $order->customer_request = trim($request->input('customer_request'));

        //Thong tin don hang
        $details_text = $request->input('details_text');
        try {
            $details = self::splitDetailText($details_text);
        } catch (\Exception $ex) {
            return $this->error($ex->getMessage());
        }

        $amount = 0;
        foreach ($details as $detail) {
            $amount += $detail->amount;
        }

        $order->ship_fee = $request->input('ship_fee');
        $order->amount = $amount;
        $order->total_amount = $amount + $order->ship_fee;
        $order->note = $request->input('note');
        $order->status = SaleOrder::$TRANG_THAI_LEN_DON;

        try {
            DB::beginTransaction();
            $order->save();
            $order->details()->saveMany($details);
            DB::commit();
            $order->load('details');
            return $this->success($order);
        } catch (\Exception $ex) {
            Log::error($ex);
            DB::rollBack();
            return $this->error($ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $sellerId = AuthUtil::getInstance()->getModel()->id;
        $validator = Validator::make($request->all(), [
            'channel' => 'required',
            'customer_phone' => 'required',
            'customer_name' => 'required',
            'customer_address' => 'required',
            'province' => 'required',
            'district' => 'required',
            'ward' => 'required',
            'ship_fee' => 'required|numeric',
            'details_text' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }

        $model = SaleOrder::with('details')->find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }

        //Hoàn về kho sau đó tính lại
        try {
            DB::beginTransaction();
            $amount = $model->amount;
            $details = [];
            if ($model->details_text != $request->input('details_text')) {
                foreach ($model->details as $d) {
                    $d->delete();
                    $ivp = InventoryProduct::where('code', $d->detail_code)->first();
                    if (empty($ivp)) {
                        InventoryProduct::create([
                            'product_id' => $d->product_id,
                            'product_code' => $d->product_code,
                            'size' => $d->size,
                            'quantity' => $d->quantity
                        ]);
                    } else {
                        $ivp->update(['quantity' => $ivp->quantity + $d->quantity]);
                    }
                }
                //Thong tin don hang
                $details_text = $request->input('details_text');
                try {
                    $details = self::splitDetailText($details_text);
                } catch (\Exception $ex) {
                    throw $ex;
                }
                $amount = 0;
                foreach ($details as $detail) {
                    $amount += $detail->amount;
                }
            }

            $model->channel = $request->input('channel');

            //Thong tin khach hang
            $model->customer_name = $request->input('customer_name');
            $model->customer_phone = $request->input('customer_phone');
            if (strlen($model->customer_phone) != 10) {
                return $this->error('SĐT khách hàng không đúng');
            }
            $model->customer_address = $request->input('customer_address');
            $model->province = $request->input('province');
            $model->district = $request->input('district');
            $model->ward = $request->input('ward');
            $model->customer_request = trim($request->input('customer_request'));


            $model->ship_fee = $request->input('ship_fee');
            $model->amount = $amount;
            $model->total_amount = $amount + $model->ship_fee;
            $model->cod_fee = $request->input('cod_fee');
            $model->payment_type = $request->input('payment_type');
            $model->banking_sms = $request->input('banking_sms');
            $model->pre_charged = $model->total_amount - $model->cod_fee;
            $model->note = $request->input('note');
            $status = $request->input('status');

            $model->status = $status;

            if ($model->shipping) {
                if ($model->shipping->deliver_id == 1) {
                    if ($model->shipping->status == 'ready_to_pick') {
                        GiaoHangNhanhUtil::updateOrder($model->shipping->code, $model);
                    }
                }
            }

            $model->save();
            $model->details()->saveMany($details);
            DB::commit();
            $model->load('details');
            return $this->success($model);
        } catch (\Exception $ex) {
            Log::error($ex);
            DB::rollBack();
            return $this->error($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

    public function verify($id, Request $request)
    {
        $sellerId = AuthUtil::getInstance()->getModel()->id;
        $model = SaleOrder::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        $model->cod_fee = $request->input('cod_fee');
        $model->payment_type = $request->input('payment_type');
        $model->banking_sms = $request->input('banking_sms');
        $model->pre_charged = $model->total_amount - $model->cod_fee;
        $note = $request->input('note');
        $model->status = SaleOrder::$TRANG_THAI_XAC_THUC;
        $model->note = $note;
        try {
            $model->save();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    public function reUpdate($id, Request $request)
    {
        $sellerId = AuthUtil::getInstance()->getModel()->id;
        $model = SaleOrder::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        $note = "Admin đã sửa lại trạng thái";
        $model->status = SaleOrder::$TRANG_THAI_CAP_NHAP_LAI;
        $model->note = $note;
        try {
            $model->save();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    public function confirm($id, Request $request)
    {
        $sellerId = AuthUtil::getInstance()->getModel()->id;
        $model = SaleOrder::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        $note = $request->input('note');
        $model->status = SaleOrder::$TRANG_THAI_CHUAN_BI_HANG;
        $model->note = $note;
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
        $sellerId = AuthUtil::getInstance()->getModel()->id;
        $model = SaleOrder::with('details')->find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        $note = $request->input('note');
        $model->status = SaleOrder::$TRANG_THAI_HUY_DON;
        $model->note = $note;
        try {
            if ($model->shipping) {
                if ($model->shipping->deliver_id == 1) {
                    GiaoHangNhanhUtil::cancelOrder($model->shipping->code);
                    $model->shipping->status = 'cancel';
                }
                if ($model->shipping->deliver_id == 2) {
                    $model->shipping->status = 'cancel';
                }
            }
            $model->save();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    public function complain($id, Request $request)
    {
        $sellerId = AuthUtil::getInstance()->getModel()->id;
        $model = SaleOrder::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        $note = $request->input('note');
        $model->status = SaleOrder::$TRANG_THAI_KHIEU_NAI;
        $model->note = $note;
        try {
            $model->save();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    public function swap($id, Request $request)
    {
        $sellerId = AuthUtil::getInstance()->getModel()->id;
        $model = SaleOrder::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        $note = $request->input('note');
        $model->status = SaleOrder::$TRANG_THAI_DOI_HANG;
        $model->note = $note;
        try {
            $model->save();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    public function refund($id, Request $request)
    {
        $sellerId = AuthUtil::getInstance()->getModel()->id;
        $model = SaleOrder::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        $note = $request->input('note');
        $model->status = SaleOrder::$TRANG_THAI_HOAN_VE;
        $model->note = $note;
        try {
            $model->save();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    public function refundAmount($id, Request $request)
    {
        $sellerId = AuthUtil::getInstance()->getModel()->id;
        $model = SaleOrder::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        $note = $request->input('note');
        $model->status = SaleOrder::$TRANG_THAI_HOAN_TIEN;
        $model->note = $note;
        try {
            $model->save();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }


    public function createSwappingOrder($id, Request $request)
    {
        try {
            DB::beginTransaction();
            $sellerId = AuthUtil::getInstance()->getModel()->id;
            $model = SaleOrder::with('details')->find($id);
            if (empty($model)) {
                return $this->error('Đối tượng không tồn tại');
            }
            $old_details = $model->details->toArray();
            $from_details_text = $request->input('from_details_text');
            $to_details_text = $request->input('to_details_text');

            try {
                $from_details = self::splitDetailText($from_details_text);
            } catch (\Exception $ex) {
                return $this->error($ex->getMessage());
            }

            foreach ($from_details as $d1) {
                $flag = false;
                foreach ($model->details as $d) {
                    if ($d1->detail_code == $d->detail_code) {
                        $flag = true;
                        $d->quantity -= $d1->quantity;
                        if ($d->quantity == 0) {
                            $d->delete();
                        } else {
                            $d->save();
                        }
                    }
                }
                if (!$flag) {
                    throw new \Exception(sprintf('Mã đổi %s không đúng', $d1->detail_code));
                }
            }

            $model->amount = 0;
            $model->load('details');
            foreach ($model->details as $detail) {
                $model->amount += $detail->amount;
            }
            $model->total_amount = $model->amount + $model->ship_fee;
            $note = 'Thông tin đổi' . PHP_EOL . $from_details_text . PHP_EOL . 'Thành' . PHP_EOL . $to_details_text;
            $model->status = SaleOrder::$TRANG_THAI_DA_DOI_HANG;
            $model->status = SaleOrder::$TRANG_THAI_DA_GIAO;
            $model->note = $note;
            $model->save();


            try {
                $to_details = self::splitDetailText($to_details_text);
            } catch (\Exception $ex) {
                return $this->error($ex->getMessage());
            }

            //Tao don hang moi de doi
            $new_order = $model->replicate();
            $new_order->code = $model->code . '_SWAP_' . time();
            $new_order->amount = 0;
            foreach ($to_details as $d) {
                $new_order->amount += $d->amount;
            }
            $new_order->ship_fee = $request->input('ship_fee');
            $new_order->total_amount = $new_order->amount + $new_order->ship_fee;
            $new_order->note = $request->input('note');
            $new_order->status = SaleOrder::$TRANG_THAI_LEN_DON;
            $new_order->cod_fee = 0;
            $new_order->banking_sms = null;
            $new_order->note = null;
            $new_order->save();
            $new_order->details()->saveMany($to_details);
            $new_order->load('details');
            DB::commit();
            return $this->success(['swapped_order' => $model, 'new_order' => $new_order]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function recreate($id, Request $request)
    {
        $sellerId = AuthUtil::getInstance()->getModel()->id;
        $model = SaleOrder::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        $model->status = SaleOrder::$TRANG_THAI_LEN_DON;
        try {
            $model->save();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    public function complete($id, Request $request)
    {
        $sellerId = AuthUtil::getInstance()->getModel()->id;
        $model = SaleOrder::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        if ($model->status == SaleOrder::$TRANG_THAI_DA_GIAO) {
            $model->is_completed = 1;
        }
        $model->status = SaleOrder::$TRANG_THAI_HOAN_THANH;

        try {
            $model->update();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

}
