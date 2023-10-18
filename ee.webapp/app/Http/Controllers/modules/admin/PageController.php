<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\RestController;
use App\Models\Page;
use App\Repository\PageRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageController extends RestController
{

    public function __construct(PageRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $search = $request->input('search', null);
        $published = $request->input('published', null);
        $limit = $request->input('limit', null);
        $query = (new Page())
            ->where('name', 'like', "%{$search}%")
            ->when(isset($published), function ($q) use ($published) {
                return $q->where('published', $published);
            })
            ->with(['article', 'meta'])
            ->withCount('blocks')
            ->orderBy('id', 'desc')
            ->orderBy('published', 'desc');
        if ($limit) {
            $data = $query->paginate($limit);
        } else {
            $data = $query->get();
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'name' => 'required|unique:pages|max:255',
            'slug' => 'nullable|unique:pages|max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $name = $request->input('name');
        $slug = $request->input('slug');
        if (!$slug) {
            $slug = Str::slug($name);
        }

        $model = new Page();
        $model->name = $name;
        $model->slug = $slug;
        $model->published = true;

        try {
            DB::beginTransaction();
            $model = $this->repository->create([
                'name' => $request->name,
                'slug' => $slug,
                'published' => $request->input('published', 0)
            ], ['article', 'meta']);
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
        if (!($model instanceof Page)) {
            return $this->errorNotFound();
        }

        $validator = $this->validateRequest($request, [
            'name' => ['nullable', 'max:255', Rule::unique('pages', 'name')->ignore($id)],
            'slug' => ['nullable', 'max:255', Rule::unique('pages', 'slug')->ignore($id)],
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = [];

        if ($request->has('name')) {
            $attributes['name'] = $request->name;
            $slug = $request->input('slug');
            if (!$slug) {
                $slug = Str::slug($request->name);
            }
            $attributes['slug'] = $slug;
        }
        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, $attributes, ['article', 'meta']);
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
        if (!($model instanceof Page)) {
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
        if (!($model instanceof Page)) {
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
