<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\PostCategory;
use App\Repository\PostCategoryRepositoryInterface;
use App\Utils\FileStorageUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostCategoryController extends RestController
{

    public function __construct(PostCategoryRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $whereClauses = [];

        if ($request->has('search')) {
            array_push($whereClauses, WhereClause::queryLike('name', $request->search));
        }

        $orderBy = $request->input('orderBy', 'published:desc,id:asc');

        $limit = $request->input('limit', null);
        if ($limit) {
            $data = $this->repository->paginate($limit, $whereClauses, $orderBy, ['parent', 'meta']);
        } else {
            $data = $this->repository->get($whereClauses, $orderBy, ['parent']);
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'name' => 'required|unique:post_categories,name|max:255',
            'slug' => 'nullable|unique:post_categories,slug|max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $slug = $request->input('slug');
        if (!$slug) {
            $slug = Str::slug($request->input('name'));
        }

        $attributes = $request->only([
            'name',
            'summary',
        ]);

        $attributes['slug'] = $slug;

        $attributes['parent_id'] = $request->input('parent_id', 0);

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

    public function show($id)
    {
        $model = PostCategory::with(['parent'])->find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        return $this->success($model);
    }

    public function update(Request $request, $id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof PostCategory)) {
            return $this->errorNotFound();
        }


        $validator = $this->validateRequest($request, [
            'name' => ['nullable', 'max:255', Rule::unique('post_categories', 'name')->ignore($id)],
            'slug' => ['nullable', 'max:255', Rule::unique('post_categories', 'slug')->ignore($id)],
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'name',
            'summary',
        ]);

        $slug = $request->input('slug');
        if (!$slug) {
            $slug = Str::slug($request->input('name'));
        }

        $attributes['slug'] = $slug;

        try {
            DB::beginTransaction();
            FileStorageUtil::getInstance()->deleteFile($model->image);
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

    public function enable($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof PostCategory)) {
            return $this->errorNotFound();
        }
        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, [
                'published' => true
            ]);
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
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof PostCategory)) {
            return $this->errorNotFound();
        }
        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, [
                'published' => false
            ]);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

}
