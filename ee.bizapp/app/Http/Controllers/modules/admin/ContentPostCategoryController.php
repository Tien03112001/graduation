<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\ContentPostCategory;
use App\Models\SeoMeta;
use App\Utils\FileUtil;
use App\Utils\HtmlUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ContentPostCategoryController extends Controller implements ApiController
{
    public function index(Request $request)
    {
        $search = $request->input('search', null);
        $limit = $request->input('limit', null);
        $query = (new ContentPostCategory)
            ->when($search, function ($q) use ($search) {
                return $q->where('name', 'like', '%' . $search . '%');
            })
            ->with('parent', 'labels', 'meta')
            ->orderBy('parent_id')
            ->orderBy('published', 'DESC');
        if ($limit) {
            $data = $query->paginate($limit);
        } else {
            $data = $query->get();
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:content_post_categories|max:255',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        $slug = $request->input('slug');
        if (!$slug) {
            $slug = Str::slug($request->input('name'));
        }
        $model = new ContentPostCategory();
        $model->name = $request->input('name');
        $model->slug = $slug;
        $model->summary = $request->input('summary');
        $model->parent_id = $request->input('parent_id', 0);
        $model->alt = $request->input('alt');
        if ($request->hasFile('image')) {
            $url = Storage::disk('public')->put('images', $request->file('image'));
            if (empty($url)) {
                return $this->error('Not Found File');
            }
            $model->image = $url;
            $model->alt = $request->input('alt', $model->name);
        }

        $meta = new SeoMeta();
        $meta->description = $request->input('meta_description', HtmlUtil::getSummaryContent($model->summary));
        $meta->keywords = $request->input('meta_keywords');
        $meta->robots = $request->input('meta_robots');
        $meta->canonical = $request->input('meta_canonical');
        $meta->more = $request->input('other_code');

        try {
            DB::beginTransaction();
            $model->save();
            $model->meta()->save($meta);
            $label = $request->input('label');
            if ($label) {
                $labels = json_decode($label);
                $label_id = [];
                foreach ($labels as $value) {
                    array_push($label_id, $value->id);
                }
                $model->labels()->sync($label_id);
            }
            DB::commit();
            $model->load(['parent']);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function show($id)
    {
        $model = ContentPostCategory::with(['parent', 'meta'])->find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        return $this->success($model);
    }

    public function update(Request $request, $id)
    {
        $slug = $request->input('slug');
        if (!$slug) {
            $slug = Str::slug($request->input('name'));
        }
        try {
            DB::beginTransaction();
            $model = (new ContentPostCategory())->find($id);
            if ($model->slug != $slug) {
                $model->slug = $slug;
            }
            $model->name = $request->input('name');
            $model->summary = $request->input('summary');
            $model->parent_id = $request->input('parent_id', 0);
            $model->published = $request->input('published', $model->published);
            $model->alt = $request->input('alt');
            if ($request->hasFile('image')) {
                $url = Storage::disk('public')->put('images', $request->file('image'));
                if (empty($url)) {
                    return $this->error('Not Found File');
                }
                FileUtil::removePublicFile($model->image);
                $model->image = $url;
                $model->alt = $request->input('alt', $model->name);
            }
            $label = $request->input('label');
            if ($label) {
                $label_id = [];
                $labels = json_decode($label);
                foreach ($labels as $value) {
                    array_push($label_id, $value->id);
                }
                $model->labels()->sync($label_id);
            } else {
                $model->labels()->detach();
            }
            $model->save();
            DB::commit();
            $model->load(['parent']);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = ContentPostCategory::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->delete();
        return $this->success($model);
    }

    public function enable($id)
    {
        $model = ContentPostCategory::find($id);
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
        $model = ContentPostCategory::find($id);
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

    public function change_order(Request $request, $id)
    {
        $data = ContentPostCategory::find($id);
        $data->order = $request->order;
        $data->save();
        return $this->success($data);
    }
}
