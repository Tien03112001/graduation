<?php

namespace App\Http\Controllers\modules\inventory;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\SaleOrder;
use App\Models\VirtualInventory;
use Illuminate\Http\Request;

class VirtualInventoryController extends Controller implements ApiController
{

    public function index(Request $request)
    {
        $orders = SaleOrder::whereIn('status', [
            SaleOrder::$TRANG_THAI_CHO_HANG,
            SaleOrder::$TRANG_THAI_CHUAN_BI_HANG,
        ])->with('details')
            ->get();

        $data = [];
        foreach ($orders as $order) {
            foreach ($order->details as $d) {
                if (empty($data[$d->detail_code])) {
                    $data[$d->detail_code] = [
                        'need' => 0,
                        'has' => null,
                    ];
                }
                $data[$d->detail_code]['need'] += $d->quantity;
            }
        }
        $virtualInventory = (new VirtualInventory())->get();
        foreach ($virtualInventory as $vi) {
            if (empty($data[$vi->code])) {
                $data[$vi->code] = [
                    'need' => 0,
                    'has' => null
                ];
            }
            $data[$vi->code]['has'] = $vi;
        }

        ksort($data);

        $rows = [];
        foreach ($data as $key => $value) {
            array_push($rows, array_merge($value, ['code' => $key,]));
        }
        return $this->success($rows);
    }

    public function store(Request $request)
    {
        // TODO: Implement store() method.
    }

    public function show($id)
    {
        // TODO: Implement show() method.
    }

    public function update(Request $request, $id)
    {
        // TODO: Implement update() method.
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }
}
