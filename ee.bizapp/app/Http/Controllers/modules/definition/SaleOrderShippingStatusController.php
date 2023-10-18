<?php

namespace App\Http\Controllers\modules\definition;

use App\Http\Controllers\Controller;
use App\Models\SaleOrderShippingStatus;

class SaleOrderShippingStatusController extends Controller
{
    public function index()
    {
        $data = SaleOrderShippingStatus::orderBy('priority')->get();
        return $this->success($data);
    }
}
