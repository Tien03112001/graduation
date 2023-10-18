<?php

namespace App\Http\Controllers\modules\manager;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\ShippingWard;
use Illuminate\Http\Request;

class ShippingWardController extends Controller implements ApiController
{
    public function index(Request $request)
    {
        $districtId = $request->input('district_id');
        $data = ShippingWard::when(isset($districtId), function ($q) use ($districtId) {
            return $q->where('district_id', $districtId);
        })
            ->with('district')
            ->get();
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
