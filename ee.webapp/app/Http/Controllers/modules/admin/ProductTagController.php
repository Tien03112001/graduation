<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\ProductRepositoryInterface;
use App\Repository\ProductTagRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductTagController extends RestController
{
    protected $productRepository;

    public function __construct(ProductTagRepositoryInterface $repository, ProductRepositoryInterface $productRepository)
    {
        parent::__construct($repository);
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        $whereClauses = [];

        if ($request->has('search')) {
            array_push($whereClauses, WhereClause::queryLike('name', $request->search));
        }

        if ($request->has('product_id')) {
            $productId = $request->product_id;
            array_push($whereClauses, WhereClause::queryRelationHas('products', function ($q) use ($productId) {
                $q->where('id', $productId);
            }));
        }

        return $this->indexDefault($request, $whereClauses, ['meta', 'article']);
    }

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'name' => ['required', 'max:255', Rule::unique('product_tags', 'name')],
            'summary' => 'nullable'
        ]);
        if ($validator) {
            return $this->error($validator);
        }
        $attributes = $request->only([
            'name',
            'summary'
        ]);
        $attributes['slug'] = Str::slug($attributes['name']);
        try {
            DB::beginTransaction();
            $model = $this->repository->create($attributes);
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
        return $this->showDefault($id, ['products']);
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validateRequest($request, [
            'name' => ['required', 'max:255', Rule::unique('product_tags', 'name')],
            'summary' => 'nullable'
        ]);
        if ($validator) {
            return $this->error($validator);
        }

        $attributes = $request->only([
            'name',
            'summary'
        ]);

        if ($request->has('name')) {
            $attributes['slug'] = Str::slug($request->name);
        }

        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, $attributes);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->errorClient($e->getMessage());
        }
    }

    public function destroy($id)
    {
        return $this->destroyDefault($id);
    }


    public function syncProducts($id, Request $request)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        $validator = $this->validateRequest($request, [
            'product_ids' => ['required'],
        ]);
        if ($validator) {
            return $this->error($validator);
        }

        $productIds = array_map(function ($v) {
            return intval(trim($v));
        }, explode(',', $request->product_ids));


        if ($this->repository instanceof ProductTagRepositoryInterface) {
            try {
                DB::beginTransaction();
                foreach ($productIds as $productId) {
                    if (empty($this->productRepository->findById($productId))) {
                        throw new \Exception("Sản phẩm $productId không tồn tại");
                    }
                }
                $this->repository->syncProducts($model, $productIds);
                DB::commit();
                return $this->success($model->load('products'));
            } catch (\Exception $e) {
                Log::error($e);
                DB::rollBack();
                return $this->errorClient($e->getMessage());
            }

        }

        return $this->error();

    }

    public function attachProducts($id, Request $request)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        $validator = $this->validateRequest($request, [
            'product_ids' => ['required'],
        ]);
        if ($validator) {
            return $this->error($validator);
        }

        $productIds = array_map(function ($v) {
            return intval(trim($v));
        }, explode(',', $request->product_ids));


        if ($this->repository instanceof ProductTagRepositoryInterface) {
            try {
                DB::beginTransaction();
                foreach ($productIds as $productId) {
                    if ($this->productRepository->findById($productId)) {
                        $this->repository->attachProduct($model, $productId);
                    } else {
                        throw new \Exception("Sản phẩm $productId không tồn tại");
                    }
                }
                DB::commit();
                return $this->success($model->load('products'));
            } catch (\Exception $e) {
                Log::error($e);
                DB::rollBack();
                return $this->errorClient($e->getMessage());
            }

        }

        return $this->error();

    }

    public function detachProducts($id, Request $request)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        $validator = $this->validateRequest($request, [
            'product_ids' => ['required'],
        ]);
        if ($validator) {
            return $this->error($validator);
        }

        $productIds = array_map(function ($v) {
            return intval(trim($v));
        }, explode(',', $request->product_ids));


        if ($this->repository instanceof ProductTagRepositoryInterface) {
            try {
                DB::beginTransaction();
                foreach ($productIds as $productId) {
                    if ($this->productRepository->findById($productId)) {
                        $this->repository->detachProduct($model, $productId);
                    } else {
                        throw new \Exception("Sản phẩm $productId không tồn tại");
                    }
                }
                DB::commit();
                return $this->success($model->load('products'));
            } catch (\Exception $e) {
                Log::error($e);
                DB::rollBack();
                return $this->errorClient($e->getMessage());
            }

        }

        return $this->error();

    }


}
