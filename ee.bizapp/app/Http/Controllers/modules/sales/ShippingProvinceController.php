<?php

namespace App\Http\Controllers\modules\sales;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\ShippingProvince;
use Illuminate\Http\Request;

class ShippingProvinceController extends Controller implements ApiController
{
    public function index(Request $request)
    {
        $data = ShippingProvince::with('districts.wards')->get();
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
