<?php

namespace App\Http\Controllers\modules\manager;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\SaleCustomer;
use Illuminate\Http\Request;

class SaleCustomerController extends Controller implements ApiController
{

    public function index(Request $request)
    {
        $limit = $request->input('limit');
        $search = $request->input('search');
        $phone = $request->input('phone');
        $sql = (new SaleCustomer())
            ->when(isset($search), function ($q) use ($search) {
                return $q->where('name', 'like', '%' . $search . '%');
            })
            ->when(isset($phone), function ($q) use ($phone) {
                return $q->where('phone', $phone);
            });
        if ($limit) {
            $data = $sql->paginate($limit);
        } else {
            $data = $sql->get();
        }
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
