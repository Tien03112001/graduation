<?php

namespace App\Http\Controllers\modules\shipments;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\InventoryProduct;
use Illuminate\Http\Request;

class InventoryProductController extends Controller implements ApiController
{

    public function index(Request $request)
    {
        $limit = $request->input('limit');
        $status = $request->input('status');
        $search = $request->input('search');
        $sql = (new InventoryProduct())
            ->when(isset($search), function ($q) use ($search) {
                return $q->where('code', 'like', '%' . $search . '%');
            })
            ->when(isset($status) && $status == 1, function ($q) {
                return $q->where('quantity', '>', 0);
            })
            ->when(isset($status) && $status == 0, function ($q) {
                return $q->where('quantity', '<=', 0);
            })
            ->with('product')
            ->orderBy('product_id', 'DESC');
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
