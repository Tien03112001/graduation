<?php

namespace App\Http\Controllers\modules\definition;

use App\Http\Controllers\Controller;
use App\Models\ShippingProvince;

class ShippingProvinceController extends Controller
{
    public function index()
    {
        $data = ShippingProvince::with('districts.wards')->get();
        return $this->success($data);
    }

}
