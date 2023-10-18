<?php

namespace App\Http\Controllers\modules\shipments;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller implements ApiController
{

    public function index(Request $request)
    {


        $prepare_orders_count = DB::table('sale_orders')
            ->where('status', 'Chuẩn bị hàng')
            ->count();

        $refund_orders_count = DB::table('sale_orders')
            ->where('status', 'Hoàn về')
            ->count();

        $tables = DB::table('sale_orders')
            ->select(['status', DB::raw('COUNT(id) as count')])
            ->groupBy('status')
            ->get();
        $tableData = [];
        foreach ($tables as $index => $row) {
            array_push($tableData, [
                [
                    'value' => $index + 1,
                    'align' => 'text-right'
                ],
                [
                    'value' => $row->status,
                    'align' => 'text-left'
                ],
                [
                    'value' => $row->count,
                    'align' => 'text-right'
                ],
            ]);
        }

        $data = [
            'boxes' => [
                [
                    'bg' => 'bg-yellow',
                    'icon' => 'fa-cube',
                    'text' => 'Cần chuẩn bị',
                    'value' => $prepare_orders_count
                ],
                [
                    'bg' => 'bg-red',
                    'icon' => 'fa-exchange',
                    'text' => 'Đang hoàn về',
                    'value' => $refund_orders_count
                ]
            ],
            'tables' => [
                [
                    'bg' => 'box-warning',
                    'title' => 'Thống kê trạng thái',
                    'label' => 'Tất cả',
                    'headers' => ['#', 'Trạng thái', 'SL'],
                    'rows' => $tableData
                ]
            ]
        ];

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
