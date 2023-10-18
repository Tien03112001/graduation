<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\ContentGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ContentGalleryController extends Controller implements ApiController
{
    public function index(Request $request)
    {
        $search = $request->input('search', null);
        $published = $request->input('published', null);
        $limit = $request->input('limit', null);
        $query = (new ContentGallery())
            ->where('name', 'like', "%{$search}%")
            ->when(isset($published), function ($q) use ($published) {
                return $q->where('published', $published);
            })
            ->with('images')
            ->orderBy('id', 'desc')
            ->orderBy('published', 'desc');
        if ($limit) {
            $data = $query->paginate($limit);
        } else {
            $data = $query->get();
        }
        return $this->success($data);
    }

    public function index_images($id)
    {
        $query = (new ContentGallery())
            ->with('images')->find($id);
        return $this->success($query->images);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:content_galleries,name|max:255',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }

        $model = new ContentGallery();
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

    public function show($id)
    {
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => "required|unique:content_galleries,name,{$id}|max:255",
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }

        $model = (new ContentGallery())->find($id);
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
        $model = ContentGallery::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->delete();
        return $this->success($model);
    }

    public function enable($id)
    {
        $model = ContentGallery::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        try {
            DB::beginTransaction();
            $model->published = true;
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
        $model = ContentGallery::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        try {
            DB::beginTransaction();
            $model->published = false;
            $model->save();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function images($id)
    {
        $model = ContentGallery::with('images')->find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        return $this->success($model->images);
    }
}
