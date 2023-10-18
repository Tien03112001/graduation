<?php

namespace App\Http\Controllers\modules\inventory;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\InventoryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller implements ApiController
{

    public function index(Request $request)
    {
        $errors_count = DB::table('inventory_products')
            ->whereRaw('quantity <= used_quantity')
            ->count();

        $out_stock_count = DB::table('inventory_products')
            ->where('quantity', 0)
            ->count();

        $tables = InventoryProduct::where('quantity', 0)
            ->with('product')
            ->orderBy('code')
            ->get();
        $tableData = [];
        foreach ($tables as $index => $row) {
            array_push($tableData, [
                [
                    'value' => $index + 1,
                    'align' => 'text-right'
                ],
                [
                    'value' => $row->code,
                    'align' => 'text-left'
                ],
                [
                    'value' => $row->product->name,
                    'align' => 'text-left'
                ],
            ]);
        }

        $data = [
            'boxes' => [
                [
                    'bg' => 'bg-red',
                    'icon' => 'fa-cube',
                    'text' => 'Vượt ngưỡng cho phép',
                    'value' => $errors_count
                ],
                [
                    'bg' => 'bg-yellow',
                    'icon' => 'fa-product-hunt',
                    'text' => 'Trạng thái hết hàng',
                    'value' => $out_stock_count
                ]
            ],
            'tables' => [
                [
                    'bg' => 'box-default',
                    'title' => 'Thống kê hết hàng',
                    'label' => 'Tất cả',
                    'headers' => ['#', 'Mã', 'Tên'],
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
