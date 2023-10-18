<?php

namespace App\Http\Controllers\modules\sales;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\SaleOrder;
use App\Utils\AuthUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller implements ApiController
{

    public function index(Request $request)
    {
        $sellerId = AuthUtil::getInstance()->getModel()->id;
        $data = [];
        $now = now();
        $data['orders'] = DB::table('sale_orders')
            ->whereMonth('sale_orders.created_at', '=', $now->month)
            ->where('seller_id', $sellerId)
            ->groupBy('status')
            ->select([
                'status',
                DB::raw('COUNT(id) AS orders_count'),
                DB::raw('SUM(amount) AS sales_value'),
            ])
            ->orderBy('sales_value', 'DESC')
            ->get();

        $data['sales'] = DB::table('sale_orders')
            ->whereMonth('sale_orders.created_at', '=', $now->month)
            ->whereIn('status', [
                SaleOrder::$TRANG_THAI_LEN_DON,
                SaleOrder::$TRANG_THAI_XAC_THUC,
                SaleOrder::$TRANG_THAI_CHUAN_BI_HANG,
                SaleOrder::$TRANG_THAI_CHO_HANG,
                SaleOrder::$TRANG_THAI_DANG_GIAO,
                SaleOrder::$TRANG_THAI_HOAN_THANH,
            ])
            ->join('users', 'users.id', '=', 'sale_orders.seller_id')
            ->groupBy(['users.id', 'users.name'])
            ->select([
                'users.id',
                'users.name',
                DB::raw('COUNT(sale_orders.id) AS orders_count'),
                DB::raw('SUM(sale_orders.amount) AS sales_value'),
                DB::raw('SUM(CASE WHEN sale_orders.is_completed = 1 THEN sale_orders.amount ELSE 0 END) AS revenue'),
            ])
            ->orderBy('sales_value', 'DESC')
            ->get();

        $daily = DB::table('sale_orders')
            ->whereDate('created_at', '=', $now->toDateString())
            ->where('seller_id', $sellerId)
            ->whereIn('status', [
                SaleOrder::$TRANG_THAI_LEN_DON,
                SaleOrder::$TRANG_THAI_XAC_THUC,
                SaleOrder::$TRANG_THAI_CHUAN_BI_HANG,
                SaleOrder::$TRANG_THAI_CHO_HANG,
                SaleOrder::$TRANG_THAI_DANG_GIAO,
                SaleOrder::$TRANG_THAI_HOAN_THANH,
            ])
            ->select([
                DB::raw('COUNT(id) AS orders_count'),
                DB::raw('SUM(amount) AS sales_value'),
            ])
            ->first();

        $data['orders_count'] = (int)$daily->orders_count;
        $data['sales_value'] = (int)$daily->sales_value;

        $error = DB::table('sale_orders')
            ->whereDate('updated_at', '=', $now->toDateString())
            ->where('seller_id', $sellerId)
            ->whereNotIn('status', [
                SaleOrder::$TRANG_THAI_LUU_NHAP,
                SaleOrder::$TRANG_THAI_LEN_DON,
                SaleOrder::$TRANG_THAI_XAC_THUC,
                SaleOrder::$TRANG_THAI_CHUAN_BI_HANG,
                SaleOrder::$TRANG_THAI_CHO_HANG,
                SaleOrder::$TRANG_THAI_DANG_GIAO,
                SaleOrder::$TRANG_THAI_HOAN_THANH,
            ])
            ->select([
                DB::raw('COUNT(id) AS error_orders_count'),
            ])
            ->first();
        $data['error_orders_count'] = (int)$error->error_orders_count;

        return $this->success($data);
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
