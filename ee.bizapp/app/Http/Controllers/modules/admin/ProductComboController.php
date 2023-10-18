<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\ContentProduct;
use App\Models\ProductCombo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductComboController extends Controller implements ApiController
{

    public function index(Request $request)
    {
        $limit = $request->input('limit');
        $search = $request->input('search');
        $sql = (new ProductCombo())
            ->when(isset($search), function ($q) use ($search) {
                return $q->where('name', 'like', '%' . $search . '%');
            })
            ->with('combo_prices')
            ->orderBy('id', 'DESC');
        if ($limit) {
            $data = $sql->paginate($limit);
        } else {
            $data = $sql->get();
        }
        $ids = [];
        foreach ($data as $d) {
            array_push($ids, $d->id);
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'sale_price' => 'required|numeric',
            'min_quantity' => 'required|numeric',
            'max_quantity' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        try {
            DB::beginTransaction();
            $model = ProductCombo::create($request->all([
                'name',
                'sale_price',
                'min_quantity',
                'max_quantity'
            ]));
            DB::commit();
            return $this->success($model);
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
        $model = ProductCombo::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'sale_price' => 'required|numeric',
            'min_quantity' => 'required|numeric',
            'max_quantity' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }

        try {
            DB::beginTransaction();
            $model->name = $request->name;
            $model->sale_price = $request->sale_price;
            $model->min_quantity = $request->min_quantity;
            $model->max_quantity = $request->max_quantity;
            $model->save();
            DB::commit();
            $model->load('products');
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = ProductCombo::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        try {
            DB::beginTransaction();
            $model->combo_prices()->delete();
            $model->delete();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function enable($id)
    {
        $model = ProductCombo::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        try {
            DB::beginTransaction();
            $model->status = true;
            $model->save();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function disable($id)
    {
        $model = ProductCombo::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        try {
            DB::beginTransaction();
            $model->status = false;
            $model->save();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function loadProducts($id)
    {
        $model = ProductCombo::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }

        return $this->success($model->products);

    }

    public function addProduct($id, Request $request)
    {
        $model = ProductCombo::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        $productIds = $request->input('products');
        $oldProductIds = $model->products()->pluck('id')->toArray();
        $newIds = array_diff($productIds, $oldProductIds);
        try {
            DB::beginTransaction();
            $model->products()->attach($newIds);
            DB::commit();
            return $this->success([]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function deleteProduct($id, $productId)
    {
        $model = ProductCombo::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        $product = ContentProduct::find($productId);
        if (empty($product)) {
            return $this->error('Sản phẩm không tồn tại');
        }
        try {
            DB::beginTransaction();
            $model->products()->detach($productId);
            DB::commit();
            return $this->success($product);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function deleteProductAll($id)
    {
        $model = ProductCombo::find($id);
        if (empty($model)) {
            return $this->error('Không tìm thấy đối tượng');
        }
        $model->products()->detach();
        return $this->success([]);
    }

}
