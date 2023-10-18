<?php

namespace App\Http\Controllers\modules\manager;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\SaleOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller implements ApiController
{

    public function index(Request $request)
    {
        $data = [];
        $now = now();
        $data['orders'] = DB::table('sale_orders')
            ->whereMonth('sale_orders.created_at', '=', $now->month)
            ->groupBy('status')
            ->select([
                'status',
                DB::raw('COUNT(id) AS orders_count'),
                DB::raw('SUM(amount) AS sales_value'),
            ])
            ->orderBy('sales_value', 'DESC')
            ->get();

        $data['sales'] = DB::table('sale_orders')
            ->whereIn('status', [
                SaleOrder::$TRANG_THAI_LEN_DON,
                SaleOrder::$TRANG_THAI_XAC_THUC,
                SaleOrder::$TRANG_THAI_CHUAN_BI_HANG,
                SaleOrder::$TRANG_THAI_CHO_HANG,
                SaleOrder::$TRANG_THAI_DANG_GIAO,
                SaleOrder::$TRANG_THAI_HOAN_THANH,
            ])
            ->whereMonth('sale_orders.created_at', '=', $now->month)
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

        $data['report_today'] = [
            'orders_count' => (int)$daily->orders_count,
            'sales_value' => (int)$daily->sales_value
        ];

        $yesterday = DB::table('sale_orders')
            ->whereDate('created_at', '=', $now->subDay()->toDateString())
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
        $data['report_yesterday'] = [
            'orders_count' => (int)$yesterday->orders_count,
            'sales_value' => (int)$yesterday->sales_value
        ];

        $error = DB::table('sale_orders')
            ->whereDate('updated_at', '=', $now->toDateString())
            ->whereNotIn('status', [
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

        $error = DB::table('sale_orders')
            ->whereDate('updated_at', '=', $now->toDateString())
            ->whereNotIn('status', [
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

        $data['report_shipping'] = DB::select(DB::raw('
        SELECT
            DATE(b.created_at) AS created_date,
            COUNT(o.id) AS orders_count
        FROM
            bill_of_ladings b
        INNER JOIN sale_orders o ON o.bol_id = b.id
        WHERE
            b.`status` = 1
        AND DATE(b.created_at) BETWEEN ? AND ?
        GROUP BY
            created_date
        ORDER BY
            created_date
	'), [now()->subDays(14)->toDateString(), now()->toDateString()]);

        $data['report_shipping'] = DB::select(DB::raw('
        SELECT
            DATE(b.created_at) AS created_date,
            COUNT(o.id) AS orders_count
        FROM
            bill_of_ladings b
        INNER JOIN sale_orders o ON o.bol_id = b.id
        WHERE
            b.`status` = 1
        AND DATE(b.created_at) BETWEEN ? AND ?
        GROUP BY
            created_date
        ORDER BY
            created_date
	'), [now()->subDays(14)->toDateString(), now()->toDateString()]);

        $data['report_sales'] = DB::select(DB::raw('
        SELECT
            DATE(o.created_at) AS created_date,
            COUNT(o.id) AS orders_count,
            SUM(o.amount) AS amount
        FROM
            sale_orders o
        WHERE
            o.`status` IN (
                ?,
                ?,
                ?,
                ?,
                ?
            )
        AND DATE(o.created_at) BETWEEN ? AND ?
        GROUP BY
            created_date
        ORDER BY
            created_date
	'), [
            SaleOrder::$TRANG_THAI_XAC_THUC,
            SaleOrder::$TRANG_THAI_CHUAN_BI_HANG,
            SaleOrder::$TRANG_THAI_CHO_HANG,
            SaleOrder::$TRANG_THAI_DANG_GIAO,
            SaleOrder::$TRANG_THAI_HOAN_THANH,
            now()->subDays(14)->toDateString(),
            now()->toDateString()
        ]);


        $data['report_backlog'] = DB::select(DB::raw('
        SELECT
            DATE(o.created_at) AS created_date,
            COUNT(o.id) AS orders_count
        FROM
            sale_orders o
        WHERE
            o.`status` IN (?,?)
        GROUP BY
            created_date
        ORDER BY
            created_date ASC
	'), [
            SaleOrder::$TRANG_THAI_CHUAN_BI_HANG,
            SaleOrder::$TRANG_THAI_CHO_HANG
        ]);

        $data['report_hot_product'] = DB::select(DB::raw('
        SELECT
        d.product_code,
        p.`name` AS `name`,
        DATE(p.created_at) AS product_date,
        d.size,
        SUM(d.quantity) AS quantity
    FROM
        sale_order_details d
    INNER JOIN sale_orders o ON o.id = d.order_id
    INNER JOIN content_products p ON p.id = d.product_id
    WHERE
        o.`status` IN (?,?,?,?,?,?,?)
    AND DATE(o.created_at) BETWEEN ? AND ?
    GROUP BY
        d.product_code,
        p.`name`,
        product_date,
        d.size
    ORDER BY
        product_date DESC, quantity DESC
	'), [
            SaleOrder::$TRANG_THAI_LEN_DON,
            SaleOrder::$TRANG_THAI_XAC_THUC,
            SaleOrder::$TRANG_THAI_CHUAN_BI_HANG,
            SaleOrder::$TRANG_THAI_CHO_HANG,
            SaleOrder::$TRANG_THAI_DANG_GIAO,
            SaleOrder::$TRANG_THAI_DA_GIAO,
            SaleOrder::$TRANG_THAI_HOAN_THANH,
            now()->subDays(7)->toDateString(),
            now()->toDateString()
        ]);

        $data['report_shipping'] = DB::select(DB::raw('
        SELECT
            DATE(b.created_at) AS created_date,
            COUNT(o.id) AS orders_count
        FROM
            bill_of_ladings b
        INNER JOIN sale_orders o ON o.bol_id = b.id
        WHERE
            b.`status` = 1
        AND DATE(b.created_at) BETWEEN ? AND ?
        GROUP BY
            created_date
        ORDER BY
            created_date
	'), [now()->subDays(14)->toDateString(), now()->toDateString()]);

        $data['report_channels'] = DB::select(DB::raw('
        SELECT
            o.channel,
            DATE(o.created_at) AS created_date,
            COUNT(o.id) AS orders_count,
            SUM(o.amount) AS amount
        FROM
            sale_orders o
        WHERE
          o.`status` IN (?,?,?,?,?)
        AND
            DATE(o.created_at) BETWEEN ? AND ?
        GROUP BY
            channel,
            created_date
        ORDER BY
            channel,
        created_date DESC
	'), [
            SaleOrder::$TRANG_THAI_XAC_THUC,
            SaleOrder::$TRANG_THAI_CHUAN_BI_HANG,
            SaleOrder::$TRANG_THAI_CHO_HANG,
            SaleOrder::$TRANG_THAI_DANG_GIAO,
            SaleOrder::$TRANG_THAI_HOAN_THANH,
            now()->subDays(7)->toDateString(),
            now()->toDateString()
        ]);

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
