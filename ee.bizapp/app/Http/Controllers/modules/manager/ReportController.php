<?php

namespace App\Http\Controllers\modules\manager;

use App\Http\Controllers\Controller;
use App\Models\SaleOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    public function backlog(Request $request)
    {
        $sellerId = $request->input('seller_id');
        $data = DB::table('sale_orders')
            ->join('users', 'users.id', '=', 'sale_orders.seller_id')
            ->when(isset($sellerId), function ($q) use ($sellerId) {
                return $q->where('sale_orders.seller_id', '=', $sellerId);
            })
            ->whereIn('sale_orders.status', [
                SaleOrder::$TRANG_THAI_XAC_THUC,
                SaleOrder::$TRANG_THAI_CHUAN_BI_HANG,
                SaleOrder::$TRANG_THAI_CHO_HANG,
            ])
            ->groupBy('created_date', 'sale_orders.status')
            ->select([
                DB::raw('DATE(sale_orders.created_at) created_date'),
                'sale_orders.status',
                DB::raw('COUNT(sale_orders.id) AS orders_count')
            ])
            ->get();

        return $this->success($data);
    }

    public function transport()
    {
        $data = DB::table('sale_orders')
            ->join('users', 'users.id', '=', 'sale_orders.seller_id')
            ->whereIn('sale_orders.status', [
                SaleOrder::$TRANG_THAI_DANG_GIAO,
            ])
            ->groupBy('created_date', 'sale_orders.status')
            ->select([
                DB::raw('DATE(sale_orders.created_at) created_date'),
                'sale_orders.status',
                DB::raw('COUNT(sale_orders.id) AS orders_count')
            ])
            ->get();

        return $this->success($data);
    }

    public function reportBillOfLadingByDate(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $data = DB::table('bill_of_ladings')
            ->where('bill_of_ladings.status', '=', 1)
            ->whereRaw('DATE(bill_of_ladings.created_at) >= ?', [$fromDate])
            ->whereRaw('DATE(bill_of_ladings.created_at) <= ?', [$toDate])
            ->join('sale_orders', 'sale_orders.bol_id', '=', 'bill_of_ladings.id')
            ->select(DB::raw('DATE(bill_of_ladings.created_at) AS created_date'), DB::raw('COUNT(sale_orders.id) as orders_count'))
            ->groupBy('created_date')
            ->orderBy('created_date', 'DESC');
        return $this->success($data);
    }
}
