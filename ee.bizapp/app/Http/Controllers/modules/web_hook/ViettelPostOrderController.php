<?php

namespace App\Http\Controllers\webhook;

use App\Http\Controllers\Controller;
use App\Models\SaleOrderShipping;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ViettelPostOrderController extends Controller
{
    public function update(Request $request)
    {

        $token = $request->input('TOKEN');
        if ($token != env('VIETTELPOST_WEBHOOK_TOKEN')) {
            return $this->error();
        }
        $data = $request->input('DATA');
        $shipping = SaleOrderShipping::where('code', $data['ORDER_NUMBER'])->with('order')->first();
        if (empty($shipping)) {
            Log::error($data);
        } else {
            Log::info($data);
            $shipping->updated_at = Carbon::createFromFormat('d/m/Y H:i:s', $data['ORDER_STATUSDATE']);
            $shipping->status = $data['STATUS_NAME'];
            $shipping->note .= '\n' . $data['NOTE'];
            $shipping->save();
            if ($data['ORDER_STATUS'] == 501) {
                $shipping->order->status = "Đã giao";
                $shipping->order->save();
            }
            if (in_array($data['ORDER_STATUS'], [107, 201])) {
                $shipping->order->status = "Hủy đơn";
                $shipping->order->save();
            }

            if (in_array($data['ORDER_STATUS'], [505])) {
                $shipping->order->status = "Hoàn về";
                $shipping->order->save();
            }

            if (in_array($data['ORDER_STATUS'], [504])) {
                $shipping->order->status = "Đã hoàn về";
                $shipping->order->save();
            }
        }

        return $this->success([]);
    }
}
