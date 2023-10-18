<?php

namespace App\Http\Controllers\modules\definition;

use App\Http\Controllers\Controller;
use App\Models\SaleOrderStatus;

class SaleOrderStatusController extends Controller
{
    public function index()
    {
        $data = SaleOrderStatus::orderBy('priority')->get();
        return $this->success($data);
    }
}
