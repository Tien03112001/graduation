<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\ContentArticle;
use App\Models\ContentPost;
use App\Models\SeoMeta;
use App\Utils\AuthUtil;
use App\Utils\FileUtil;
use App\Utils\HtmlUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ContentPostController extends Controller implements ApiController
{
    public function index(Request $request)
    {
        $search = $request->input('search', null);
        $categoryId = $request->input('category_id', null);
        $published = $request->input('published', null);
        $limit = $request->input('limit', null);
        $query = (new ContentPost)
            ->when($search, function ($q) use ($search) {
                return $q->where('name', 'like', '%' . $search . '%');
            })
            ->when($categoryId, function ($q) use ($categoryId) {
                return $q->where('category_id', $categoryId);
            })
            ->when(isset($published), function ($q) use ($published) {
                return $q->where('published', $published);
            })
            ->with(['category', 'labels', 'meta', 'article', 'tags', 'gallery'])
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
        $auth = AuthUtil::getInstance()->getModel();
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255'
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        $content = $request->input('content');
        $label = json_decode($request->input('label', '[]'));
        $title = $request->input('name');
        $slug = $request->input('slug', Str::slug($title));
        $createdImages = [];
        if ($content) {
            $content = HtmlUtil::minify($request->input('content'), $createdImages);
        }

        $model = new ContentPost();
        $model->name = $title;
        $model->slug = $slug;
        $model->summary = $request->input('summary');
        $model->category_id = $request->input('category_id', 0);
        $model->category_slug = $request->input('category_slug');

        if ($request->hasFile('image')) {
            $url = Storage::disk('public')->put('images', $request->file('image'));
            if (empty($url)) {
                return $this->error('Not Found File');
            }
            $model->image = $url;
            $model->alt = $request->input('alt', $model->name);
        }

        $article = new ContentArticle();
        $article->title = $request->input('title', $title);
        $article->content = $content;
        $article->author_name = $request->input('author_name', $auth->name);
        $article->author_url = $request->input('author_url', '/');

        $meta = new SeoMeta();
        $meta->description = $request->input('meta_description', HtmlUtil::getSummaryContent($model->summary ?? $content));
        $meta->keywords = $request->input('meta_keywords');
        $meta->robots = $request->input('meta_robots');
        $meta->canonical = $request->input('meta_canonical');
        $meta->more = $request->input('other_code');

        try {
            DB::beginTransaction();
            $model->save();
            $model->article()->save($article);
            $model->meta()->save($meta);
            if ($label) {
                $label_id = [];
                foreach ($label as $value) {
                    array_push($label_id, $value->id);
                }
                $model->labels()->sync($label_id);
            }
            DB::commit();
            $model->load('category', 'article', 'labels');
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            FileUtil::removePublicFile($createdImages);
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
            'name' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        $title = $request->input('name');
        $label = $request->input('label');
        $content = $request->input('content');
        if ($content) {
            $createdImages = [];
            $content = HtmlUtil::minify($request->input('content'), $createdImages);
        }
        $slug = $request->input('slug', Str::slug($title));
        $model = (new ContentPost())->find($id);
        $model->slug = $slug;
        $model->name = $title;
        $model->summary = $request->input('summary');
        $model->category_id = $request->input('category_id');
        $model->category_slug = $request->input('category_slug');
        if ($request->hasFile('image')) {
            $url = Storage::disk('public')->put('images', $request->file('image'));
            if (empty($url)) {
                return $this->error('Not Found File');
            }
            $model->image = $url;
            $model->alt = $request->input('alt', $model->name);
        }

        $article = (new ContentArticle())->where('articleable_type', 'posts')->where('articleable_id', $id)->first();
        $article->title = $request->input('title', $article->title);
        $article->content = $content;
        try {
            DB::beginTransaction();
            $model->save();
            $model->article()->save($article);
            if ($label) {
                $labels = json_decode($label);
                $label_ids = [];
                foreach ($labels as $value) {
                    array_push($label_ids, $value->id);
                }
                $model->labels()->sync($label_ids);
            }
            DB::commit();
            $model->load(['category', 'tags', 'article']);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = ContentPost::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->delete();
        return $this->success($model);
    }

    public function tags($id, Request $request)
    {
        $model = ContentPost::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $validator = Validator::make($request->all(), [
            'ids' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        $tags = array_map('intval', explode(',', $request->input('ids')));
        try {
            DB::beginTransaction();
            $model->tags()->detach();
            $model->tags()->attach($tags);
            DB::commit();
            $model->load(['tags']);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return response()->json(['message' => 'error'], 500);
        }
    }


    public function related_posts($id, Request $request)
    {
        $model = ContentPost::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $validator = Validator::make($request->all(), [
            'ids' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        $related_posts = array_map('intval', explode(',', $request->input('ids')));
        try {
            DB::beginTransaction();
            $model->related_posts()->detach();
            $model->related_posts()->attach($related_posts);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return response()->json(['message' => 'error'], 500);
        }
    }

    public function enable($id)
    {
        $model = ContentPost::find($id);
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
        $model = ContentPost::find($id);
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

    public function delete_post(Request $request)
    {
        $item = $request->input('item');
        for ($i = 0; $i < count($item); $i++) {
            $model = ContentPost::find($item[$i]);
            $model->article()->delete();
            $model->tags()->detach();
            $model->labels()->detach();
            FileUtil::removePublicFile($model->image);
        }
        return $this->success('Success');
    }

    public function change_order(Request $request, $id)
    {
        $data = ContentPost::find($id);
        $data->order = $request->order;
        $data->save();
        return $this->success($data);
    }
}
