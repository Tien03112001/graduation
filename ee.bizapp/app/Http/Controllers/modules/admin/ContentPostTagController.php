<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\ContentPostTag;
use App\Models\SeoMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ContentPostTagController extends Controller implements ApiController
{
    public function index(Request $request)
    {
        $search = $request->input('search', null);
        $limit = $request->input('limit', null);
        $query = (new ContentPostTag)
            ->when($search, function ($q) use ($search) {
                return $q->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('id', 'desc')
//            ->orderBy('published', 'desc')
        ;
        if ($limit) {
            $data = $query->paginate($limit);
        } else {
            $data = $query->get();
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $title = $request->input('name');
        $slug = $request->input('slug', Str::slug($title));

        $model = new ContentPostTag();
        $model->name = $title;
        $model->slug = $slug;

        $meta = new SeoMeta();
        $meta->description = $request->input('meta_description', 'Tag: ' . $title);
        $meta->keywords = $request->input('meta_keywords');
        $meta->robots = $request->input('meta_robots');
        $meta->canonical = $request->input('meta_canonical');
        $meta->more = $request->input('other_code');
        try {
            DB::beginTransaction();
            $model->save();
            $model->meta()->save($meta);
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
    }

    public function update(Request $request, $id)
    {
        $title = $request->input('name');

        $model = (new ContentPostTag())->find($id);
        $model->name = $title;
        $model->slug = $request->input('slug', $model->slug);

        try {
            DB::beginTransaction();
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
        $model = ContentPostTag::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->posts()->detach();
        $model->delete();
        return $this->success($model);
    }
}
