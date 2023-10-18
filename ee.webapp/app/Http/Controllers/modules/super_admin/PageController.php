<?php

namespace App\Http\Controllers\modules\super_admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use App\Models\MetaData;
use App\Models\Page;
use App\Utils\FileUtil;
use App\Utils\HtmlUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PageController extends Controller
{
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
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|unique:pages|max:255',
                'slug' => 'unique:pages',
                'content' => 'required',
            ],
            [
                'name.unique' => 'Tên đã tồn tại',
                'name.required' => 'Tên không được để trống.',
                'content.required' => 'Nội dung không được để trống.',
                'slug.unique' => 'Đường dẫn đã tồn tại'
            ]
        );
        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }
        $createdImages = [];
        $name = $request->input('name');
        $title = $request->input('title');
        $content = HtmlUtil::minify($request->input('content'), $createdImages);
        $slug = $request->input('slug');
        if (!$slug) {
            $slug = Str::slug($name);
        }

        $model = new Page();
        $model->name = $name;
        $model->slug = $slug;
        $model->published = true;

        $article = new Article();
        $article->title = $title;
        $article->content = $content;
        $article->author_name = $request->input('author_name') ?? 'Quản trị viên';
        $article->author_url = $request->input('author_url') ??  Config::get('app.url');

        $meta = new MetaData();
        $meta->description = HtmlUtil::getSummaryContent($content);
        $meta->title = $title;
        try {
            DB::beginTransaction();
            $model->save();
            $model->article()->save($article);
            $model->meta()->save($meta);
            DB::commit();
            $model->load(['article', 'meta']);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            FileUtil::removePublicFile($createdImages);
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $model = (new Page())->find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $name = $request->input('name');
        $title = $request->input('title');
        //$content = HtmlUtil::minify($request->input('content'), $createdImages);
        $slug = $request->input('slug');
        if (!$slug) {
            $slug = Str::slug($name);
        }
        if ($model->slug != $slug) {
            $model->slug = $slug;
        }

        $model->name = $name;
        $model->slug = $slug;
        $model->published = true;

        $article = $model->article;
//        $article->title = $title;
        $article->author_name = $request->input('author_name') ?? 'Quản trị viên';
        $article->author_url = $request->input('author_url') ??  Config::get('app.url');
        try {
            DB::beginTransaction();
            $model->save();
            $article->save();
            DB::commit();
            $model->load(['article', 'meta']);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = Page::with(['article', 'meta'])->find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->article()->delete();
        Comment::where('content_article_id', $model->article->id)->delete();
        $model->meta()->delete();
        $model->delete();
        return $this->success($model);
    }

    public function enable($id)
    {
        $model = Page::find($id);
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
        $model = Page::find($id);
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
}
