<?php

namespace App\Http\Controllers\modules\shipments;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\SaleOrderShippingPickAddress;
use Illuminate\Http\Request;

class SaleOrderShippingPickAddressController extends Controller implements ApiController
{
    public function index(Request $request)
    {
        $data = SaleOrderShippingPickAddress::all();
        return $this->success($data);
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }


}
