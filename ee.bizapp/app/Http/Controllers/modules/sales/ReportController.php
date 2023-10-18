<?php

namespace App\Http\Controllers\modules\sales;

use App\Http\Controllers\Controller;
use App\Models\SaleOrder;
use App\Utils\AuthUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    public function backlog(Request $request)
    {
        $sellerId = AuthUtil::getInstance()->getModel()->id;
        $data = DB::table('sale_orders')
            ->join('users', 'users.id', '=', 'sale_orders.seller_id')
            ->whereIn('sale_orders.status', [
                SaleOrder::$TRANG_THAI_XAC_THUC,
                SaleOrder::$TRANG_THAI_CHUAN_BI_HANG,
                SaleOrder::$TRANG_THAI_CHO_HANG,
            ])
            ->where('sale_orders.seller_id', '=', $sellerId)
            ->groupBy('created_date', 'sale_orders.status')
            ->select([
                DB::raw('DATE(sale_orders.created_at) created_date'),
                'sale_orders.status',
                DB::raw('COUNT(sale_orders.id) AS orders_count')
            ])
            ->get();

        return $this->success($data);
    }

}
