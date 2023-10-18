<?php

namespace App\Http\Controllers\modules\definition;

use App\Http\Controllers\Controller;
use App\Models\ShippingService;
use Illuminate\Http\Request;

class ShippingServiceController extends Controller
{
    public function index(Request $request)
    {
        $unitId = $request->input('unit_id');
        $data = ShippingService::where('unit_id', $unitId)->orderBy('is_often', 'desc')->get();
        return $this->success($data);
    }

}
