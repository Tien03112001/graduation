<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\ContentProduct;
use App\Models\ProductInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductInventoryController extends Controller implements ApiController
{

    public function index(Request $request)
    {
        $productId = $request->input('product_id');
        $data = (new ProductInventory())
            ->whereProductId($productId)
            ->with('product')
            ->get();
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'size' => 'required|max:255',
            'quantity' => 'required|numeric',
            'product_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        $sizes = preg_split('/\W+/', $request->size, -1, PREG_SPLIT_NO_EMPTY);
        $models = [];
        try {
            DB::beginTransaction();
            foreach ($sizes as $size) {
                $model = ProductInventory::create([
                    'warehouse_id' => 1,
                    'product_id' => $request->product_id,
                    'color' => 'Only',
                    'size' => $size,
                    'quantity' => $request->quantity,
                ]);
                array_push($models, $model);
            }
            DB::commit();
            return $this->success($models);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function show($id)
    {
        // TODO: Implement show() method.
    }

    public function update(Request $request, $id)
    {
        $model = ProductInventory::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }

        try {
            DB::beginTransaction();
            if ($request->quantity > 0) {
                $model->quantity = $request->quantity;
                $model->save();
            } else {
                $model->delete();
            }
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = ProductInventory::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        try {
            DB::beginTransaction();
            $model->delete();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function deleteAll(Request $request)
    {
        $id = $request->input('product_id');
        $model = ContentProduct::find($id);
        if (empty($model)) {
            return $this->error('Không tìm thấy đối tượng');
        }
        $model->inventories()->delete();
        return $this->success([]);
    }

}
