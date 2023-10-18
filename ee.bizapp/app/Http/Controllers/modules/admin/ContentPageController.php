<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\ContentArticle;
use App\Models\ContentComment;
use App\Models\ContentPage;
use App\Models\SeoMeta;
use App\Utils\FileUtil;
use App\Utils\HtmlUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ContentPageController extends Controller implements ApiController
{
    public function index(Request $request)
    {
        $search = $request->input('search', null);
        $published = $request->input('published', null);
        $limit = $request->input('limit', null);
        $query = (new ContentPage())
            ->where('name', 'like', "%{$search}%")
            ->when(isset($published), function ($q) use ($published) {
                return $q->where('published', $published);
            })
            ->with(['article', 'meta'])
            ->orderBy('id', 'desc')
            ->orderBy('published', 'desc');
        if ($limit) {
            $data = $query->paginate($limit);
        } else {
            $data = $query->get();
        }
        return $this->success($data);
    }

    public function show($id)
    {
        // TODO: Implement show() method.
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:content_pages|max:255',
            'content' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        $createdImages = [];
        $name = $request->input('name');
        $title = $request->input('title');
        $content = HtmlUtil::minify($request->input('content'), $createdImages);
        $slug = $request->input('slug');
        if (!$slug){
            $slug = Str::slug($name);
        }

        $model = new ContentPage();
        $model->name = $name;
        $model->slug = $slug;
        $model->published = true;

        $article = new ContentArticle();
        $article->title = $title;
        $article->content = $content;
        $article->author_name = $request->input('author_name') ?? 'Quản trị viên';
        $article->author_url = $request->input('author_url') ?? config('app.url');

        $meta = new SeoMeta();
        $meta->description = HtmlUtil::getSummaryContent($content);

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
        $model = (new ContentPage())->find($id);
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
        $article->title = $title;
        //$article->content = $content;
        $article->author_name = $request->input('author_name') ?? 'Quản trị viên';
        $article->author_url = $request->input('author_url') ?? config('app.url');
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
        $model = ContentPage::with(['article', 'meta'])->find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->article()->delete();
        ContentComment::where('content_article_id', $model->article->id)->delete();
        $model->meta()->delete();
        $model->delete();
        return $this->success($model);
    }

    public function enable($id)
    {
        $model = ContentPage::find($id);
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
        $model = ContentPage::find($id);
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
