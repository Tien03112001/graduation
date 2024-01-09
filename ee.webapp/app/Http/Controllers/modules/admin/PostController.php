<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\Article;
use App\Models\Post;
use App\Models\PostCategory;
use App\Repository\ArticleRepositoryInterface;
use App\Repository\PostCategoryRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use App\Utils\FileStorageUtil;
use App\Utils\HtmlUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PostController extends RestController
{
    protected $postCategoryRepository;
    protected $articleRepository;

    public function __construct(PostRepositoryInterface $repository, PostCategoryRepositoryInterface $postCategoryRepository,
                                ArticleRepositoryInterface $articleRepository)
    {
        parent::__construct($repository);
        $this->postCategoryRepository = $postCategoryRepository;
        $this->articleRepository = $articleRepository;
    }

    public function index(Request $request)
    {
        $searchCategoryId = $request->input('category_id');
        $search = $request->input('search');
        $published = $request->input('published');
        $tag_id = $request->input('tag_id');

        $whereClauses = [];
        if (isset($searchCategoryId)) {
            array_push($whereClauses, WhereClause::query('category_id', $searchCategoryId));
        }
        if (isset($search)) {
            array_push($whereClauses, WhereClause::queryLike('name', $search));
        }
        if (isset($published)) {
            array_push($whereClauses, WhereClause::query('published', $published));
        }
        if (isset($tag_id)) {
            array_push($whereClauses, WhereClause::queryRelationHas('tags', function ($que) use ($tag_id) {
                $que->where('tag_id', $tag_id);
            }));
        }
        $orderBy = $request->input('orderBy', 'id:desc,published:desc');

        return $this->indexDefault($request, $whereClauses, ['category', 'meta', 'article', 'tags', 'gallery'], [], $orderBy);
    }

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'name' => 'required|max:255',
            'category_id' => 'required|numeric',
            'content' => 'required'
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $categoryId = $request->category_id;

        $category = $this->postCategoryRepository->findById($categoryId);
        if (empty($category)) {
            return $this->errorClient('Danh mục không tìm thấy');
        }

        if (!($category instanceof PostCategory)) {
            return $this->errorClient('Danh mục không tìm thấy');
        }

        $attributes = $request->only([
            'name',
            'summary',
            'category_id'
        ]);
        $attributes['slug'] = $request->input('slug', Str::slug($attributes['name']));
        $attributes['category_slug'] = $category->slug;


        $reserveAt = $request->input('reserve_at');
        if (isset($reserveAt)) {
            $attributes['published'] = false;
            $attributes['reserve_at'] = Carbon::parse($reserveAt)->toDateTimeString();
        } else {
            $attributes['published'] = $request->input('published', 0);
        }

        $createdImages = [];
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $filename = 'http://localhost:8000/storage/'.time() . '.' . $extenstion;

            $file->move('storage', $filename);
            $attributes['image'] = $filename;
        }
        $content = HtmlUtil::minify($request->input('content'), $createdImages);
        $article = [
            'title' => $attributes['name'],
            'content' => $content,
            'author_name' => $request->author_name,
            'author_url' => $request->input('author_url', '/'),
        ];

        $attributes['article'] = $article;

        try {
            DB::beginTransaction();
            $model = $this->repository->create($attributes, ['category', 'article']);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            FileStorageUtil::getInstance()->deleteFiles($createdImages);
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
        if (!($model instanceof Post)) {
            return $this->errorNotFound();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ], [
            'name.required' => "Tiêu đề không được để trống"
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        $title = $request->input('name');
        $content = $request->input('content');
        $createdImages = [];
        if ($content) {
            $content = HtmlUtil::minify($request->input('content'), $createdImages);
        }
        $slug = Str::slug($title);
        $model = (new Post())->find($id);
        $model->slug = $slug;
        $model->name = $title;
        $model->summary = $request->input('summary');
        $model->category_id = $request->input('category_id');
        $model->category_slug = $request->input('category_slug');

        if (isset($model->reserve_at)) {
            $model->published = 0;
        } else {
            $model->published = $request->input('published', $model->published);
        }

        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $filename = 'http://localhost:8000/storage/'.time() . '.' . $extenstion;

            $file->move('storage', $filename);
            $model->image= $filename;
        }

        $article = (new Article())->where('articleable_type', 'posts')->where('articleable_id', $id)->first();
        $article->title = $title;
        $article->content = $content;
        try {
            DB::beginTransaction();
            $model->save();
            $model->article()->save($article);
            DB::commit();
            $model->load(['category', 'tags', 'article']);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            FileStorageUtil::getInstance()->deleteFiles($createdImages);
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof Post)) {
            return $this->errorNotFound();
        }
        try {
            DB::beginTransaction();
            $this->repository->delete($model);
            FileStorageUtil::getInstance()->deleteFile($model->image);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function tags($id, Request $request)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof Post)) {
            return $this->errorNotFound();
        }

        $validator = $this->validateRequest($request, [
            'ids' => 'required',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $tags = array_map('intval', explode(',', $request->input('ids')));

        $repository = $this->repository;
        if (!($repository instanceof PostRepositoryInterface)) {
            return $this->error();
        }
        try {
            DB::beginTransaction();
            $model = $repository->syncTags($model, $tags);
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
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof Post)) {
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
        if (!($model instanceof Post)) {
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

    public function up($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorClient('Bài viết không tồn tại');
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
            return $this->errorClient('Bài viết không tồn tại');
        }

        $swapModel = $this->repository->find([WhereClause::query('order', $model->order, '>')]);

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
