<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\Gallery;
use App\Repository\GalleryRepositoryInterface;
use App\Utils\FileStorageUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class GalleryController extends RestController
{

    public function __construct(GalleryRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $orderBy = $request->input('orderBy', 'published:desc,id:desc');

        if ($request->has('search')) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        }

        if ($request->has('published')) {
            array_push($clauses, WhereClause::query('published', $request->published));
        }

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, ['galleryable'], ['images']);
        } else {
            $data = $this->repository->get($clauses, $orderBy, ['galleryable'], ['images']);
        }
        return $this->success($data);
    }

    public function index_images($id)
    {
        $query = (new Gallery())
            ->with('images')->find($id);
        return $this->success($query->images);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:galleries,name|max:255',
        ], [
            'name.required' => "Tên không được để trống",
            'name.unique' => "Tên đã tồn tại",
            "name.max" => "Tên không được vượt quá 255 ký tự"
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }

        $model = new Gallery();
        $model->name = $request->input('name');
        $model->description = $request->input('description');
        $model->galleryable_type = $request->input('galleryable_type');
        $model->galleryable_id = $request->input('galleryable_id');
        $model->published = true;

        try {
            DB::beginTransaction();
            $model->save();
            DB::commit();
            $model->load('images');
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => "required|unique:galleries,name,{$id}|max:255",
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }

        $model = (new Gallery())->find($id);
        $model->name = $request->input('name', $model->name);
        $model->description = $request->input('description', $model->description);


        try {
            DB::beginTransaction();
            $model->save();
            DB::commit();
            $model->load('images');
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = $this->repository->findById($id, ['images']);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof Gallery)) {
            return $this->errorNotFound();
        }
        try {
            DB::beginTransaction();
            $images = [];
            foreach ($model->images as $image) {
                array_push($images, $image->path);
            }
            FileStorageUtil::getInstance()->deleteFiles($images);
            $this->repository->delete($model);
            DB::commit();
            return $this->success([]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function enable($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof Gallery)) {
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
        if (!($model instanceof Gallery)) {
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
