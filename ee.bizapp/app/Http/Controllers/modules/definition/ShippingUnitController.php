<?php

namespace App\Http\Controllers\modules\definition;

use App\Http\Controllers\Controller;
use App\Models\ShippingUnit;

class ShippingUnitController extends Controller
{
    public function index()
    {
        $data = ShippingUnit::get();
        return $this->success($data);
    }

}
