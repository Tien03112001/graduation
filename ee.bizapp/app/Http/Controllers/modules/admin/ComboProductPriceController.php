<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\ComboProductPrice;
use App\Models\ContentProduct;
use App\Models\ProductCombo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ComboProductPriceController extends Controller implements ApiController
{

    public function index(Request $request)
    {
        $limit = $request->input('limit');
        $search = $request->input('search');
        $comboId = $request->combo_id;
        $productId = $request->product_id;
        $sql = (new ComboProductPrice())
            ->when(isset($search), function ($q) use ($search) {
                return $q->whereHas('product', function (Builder $q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('code', 'like', '%' . $search . '%');
                });
            })
            ->when(isset($comboId), function ($q) use ($comboId) {
                return $q->where('combo_id', $comboId);
            })
            ->when(isset($productId), function ($q) use ($productId) {
                return $q->where('product_id', $productId);
            })
            ->with('product', 'combo');
        if ($limit) {
            $data = $sql->paginate($limit);
        } else {
            $data = $sql->get();
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'combo_id' => 'required|numeric',
            'product_id' => 'required|numeric',
            'sale_price' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        try {
            DB::beginTransaction();
            $model = ComboProductPrice::create($request->all([
                'combo_id',
                'product_id',
                'sale_price',
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
        $model = ComboProductPrice::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        $validator = Validator::make($request->all(), [
            'sale_price' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }

        try {
            DB::beginTransaction();
            $model->sale_price = $request->sale_price;
            $model->save();
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
        $model = ComboProductPrice::find($id);
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

    public function bulk($id, Request $request)
    {
        $model = ProductCombo::with('combo_prices')->find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        $lines = preg_split("/\r\n|\n|\r/", $request->data);
        $allProducts = ContentProduct::all();
        $dictProducts = [];
        foreach ($allProducts as $p) {
            $dictProducts[$p->code] = $p;
        }
        $existComboProductPrices = array_map(function ($element) {
            return $element['product_id'];
        }, $model->combo_prices->toArray());
		
		$mapComboProductPrices = [];
		
		foreach ($model->combo_prices as $price){
			$mapComboProductPrices[$price->product_id] = $price;
		}

        $comboPrices = [];

        foreach ($lines as $index => $line) {
			$comboPrice = new ComboProductPrice();
			$comboPrice->combo_id = $id;
            $row = explode(',', $line);
            if (count($row) < 2) {
                return $this->error('Lỗi dữ liệu DÒNG ' . ($index + 1));
            }
            $productCode = $row[0];
            if (!array_key_exists($productCode, $dictProducts)) {
                return $this->error('Sản phẩm không tồn tại DÒNG ' . ($index + 1));
            }
            $productId = $dictProducts[$productCode]->id;
			$comboPrice->product_id = $productId;
            if (array_key_exists($productId, $mapComboProductPrices)) {
				$comboPrice = $mapComboProductPrices[$productId];
            }
            try {
                $salePrice = intval($row[1]);
            } catch (\Exception $e) {
                return $this->error('Giá lỗi dòng ' . ($index + 1));
            }
            $comboPrice->sale_price = $salePrice;
			$comboPrice->save();
            array_push($comboPrices, $comboPrice);
            $mapComboProductPrices[$productId] = $comboPrice;
        }

        try {
            DB::beginTransaction();
            $model->combo_prices()->saveMany($comboPrices);
            DB::commit();
            return $this->success([]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function deleteByProduct($id)
    {
        $model = ContentProduct::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        try {
            DB::beginTransaction();
            $model->combo_prices()->delete();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function deleteByCombo($id)
    {
        $model = ProductCombo::find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        try {
            DB::beginTransaction();
            $model->combo_prices()->delete();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

}
