<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\ProductVariantRepositoryInterface;
use App\Repository\InventoryProductRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductVariantController extends RestController
{
    protected $iventoryProductRepository;
    protected $productRepository;

    public function __construct(ProductVariantRepositoryInterface $repository, ProductRepositoryInterface $productRepository, InventoryProductRepositoryInterface $iventoryProductRepository)
    {
        parent::__construct($repository);
        $this->iventoryProductRepository = $iventoryProductRepository;
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'product_id' => ['required', 'numeric'],
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $with = ['inventory_products'];
        $whereClauses = [];
        array_push($whereClauses, WhereClause::query('product_id', $request->product_id));
        return $this->indexDefault($request, $whereClauses, $with);
    }

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'product_id' => ['required', 'numeric'],
            'name' => ['required'],
        ]);
        if ($validator) {
            return $this->error($validator);
        }

        $product = $this->productRepository->findById($request->product_id);
        if (empty($product)) {
            return $this->errorNotFound();
        }

        try {
            DB::beginTransaction();
            $variantProduct['product_id'] = $request->input('product_id');
            $variantProduct['name'] = $request->input('name');
            // $variantProduct['weight'] = 0;
            // $variantProduct['length'] = 0;
            // $variantProduct['width'] = 0;
            // $variantProduct['height'] = 0;
            $name_test = $this->repository->find([WhereClause::query('name', $request->input('name')), WhereClause::query('product_id', $request->input('product_id'))]);
            if ($name_test) {
                return $this->errorClient(' Size ' . $request->input('name') . ' đã tồn tại');
            }
            $model = $this->repository->create($variantProduct);
            $iventoryProduct['product_id'] = $request->input('product_id');
            $iventoryProduct['product_code'] = $product->code;
            $iventoryProduct['variant_id'] = $model->id;
            $iventoryProduct['size'] = 0;
            $iventoryProduct['code'] = $model->name;
            $iventoryProduct['quantity'] = 50;
            $this->iventoryProductRepository->create($iventoryProduct);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
        $model = $this->repository->findById($id);

        if (empty($model)) {
            return $this->errorNotFound();
        }
        $validator = $this->validateRequest($request, [
            'name' => 'nullable'
        ]);
        if ($validator) {
            return $this->error($validator);
        }

        $attributes['name'] = $request->name;

        $name_test = $this->repository->find([WhereClause::query('name', $request->name), WhereClause::query('product_id', $request->product_id)]);
        if ($name_test) {
            return $this->errorClient(' Size ' . $request->input('name') . ' đã tồn tại');
        }

        try {
            DB::beginTransaction();
            $inventoryProduct = $this->iventoryProductRepository->find([WhereClause::query('variant_id', $model->id), WhereClause::query('product_id',  $model->product_id)]);
            if ($inventoryProduct) {
                $this->iventoryProductRepository->update($inventoryProduct->id, ['code' => $request->name]);
            }
            $model = $this->repository->update($id, $attributes);
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
        $model = $this->repository->findById($id, 'inventory_products');
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if ($model->inventory_products->quantity > 0) {
            return $this->errorClient('Không thể xóa do còn hàng');
        }
        try {
            DB::beginTransaction();
            $this->repository->delete($model, ['inventory_products']);
            DB::commit();
            return $this->success([]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}
