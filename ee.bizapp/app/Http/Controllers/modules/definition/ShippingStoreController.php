<?php

namespace App\Http\Controllers\modules\definition;

use App\Http\Controllers\Controller;
use App\Models\ShippingStore;
use Illuminate\Http\Request;

class ShippingStoreController extends Controller
{
    public function index(Request $request)
    {
        $unitId = $request->input('unit_id');
        $data = ShippingStore::where('unit_id', $unitId)->orderBy('is_often', 'desc')->get();
        return $this->success($data);
    }

}
