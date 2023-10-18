<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\ProductCategoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ProductCategoryController extends RestController
{
    public function __construct(ProductCategoryRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $whereClauses = [];
        if (isset($search)) {
            array_push($whereClauses, WhereClause::queryLike('name', $search));
        }
        return $this->indexDefault($request, $whereClauses, ['parent', 'meta', 'article'], []);
    }

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'parent_id' => ['required', 'numeric'],
            'name' => 'required|max:255|unique:App\Models\ProductCategory,name',
            'slug' => 'required|max:255|unique:App\Models\ProductCategory,slug',
            'summary' => 'nullable'
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'parent_id',
            'name',
            'slug',
            'summary',
        ]);

        $lastProduct = $this->repository->find([], 'order:desc');
        if (isset($lastProduct)) {
            $attributes['order'] = $lastProduct->order + 1;
        }

        try {
            DB::beginTransaction();
            $model = $this->repository->create($attributes, ['parent']);
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
        $validator = $this->validateRequest($request, [
            'parent_id' => 'nullable|numeric',
            'name' => ['nullable', 'max:255', Rule::unique('product_categories', 'name')->ignore($id)],
            'slug' => ['nullable', 'max:255', Rule::unique('product_categories', 'slug')->ignore($id)],
            'summary' => 'nullable'
        ]);
        if ($validator) {
            return $this->error($validator);
        }

        $attributes = [];

        if ($request->has('parent_id')) {
            $attributes['parent_id'] = $request->parent_id;
        }

        if ($request->has('name')) {
            $attributes['name'] = $request->name;
        }

        if ($request->has('slug')) {
            $attributes['slug'] = $request->slug;
        }

        if ($request->has('summary')) {
            $attributes['summary'] = $request->summary;
        }

        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, $attributes, ['parent']);
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
        return $this->destroyDefault($id);
    }

    public function up($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorClient('Danh mục sản phẩm không tồn tại');
        }

        $swapModel = $this->repository->find([WhereClause::query('order', $model->order, '<')]);

        if (empty($swapModel)) {
            return $this->errorClient('Không thể tăng thứ hạng');
        }
        try {
            DB::beginTransaction();
            $order = $model->order;
            $model = $this->repository->update($id,
                ['order' => $swapModel->order]
            );
            $swapModel = $this->repository->update($swapModel->id,
                ['order' => $order]
            );
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function down($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorClient('Danh mục sản phẩm không tồn tại');
        }

        $swapModel = $this->repository->find([WhereClause::query('order', $model->order , '>')]);

        if (empty($swapModel)) {
            return $this->errorClient('Không thể giảm thứ hạng');
        }
        try {
            DB::beginTransaction();
            $order = $model->order;
            $model = $this->repository->update($id,
                ['order' => $swapModel->order]
            );
            $swapModel = $this->repository->update($swapModel->id,
                ['order' => $order]
            );
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

}
