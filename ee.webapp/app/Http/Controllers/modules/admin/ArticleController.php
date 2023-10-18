<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\Article;
use App\Repository\ArticleRepositoryInterface;
use App\Utils\FileStorageUtil;
use App\Utils\HtmlUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ArticleController extends RestController
{

    public function __construct(ArticleRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }


    public function index(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'articleable_id' => 'required|numeric',
            'articleable_type' => 'required|exists:meta_data,metaable_type'
        ]);

        if ($validator) {
            return $this->errorClient($validator);
        }

        $clauses = [
            WhereClause::query('articleable_type', $request->articleable_type),
            WhereClause::query('articleable_id', $request->articleable_id),
        ];

        $model = $this->repository->find($clauses);
        if (empty($model)) {
            return $this->error('Not found');
        }
        return $this->success($model);
    }


    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'title' => 'required|max:255',
            'content' => 'required',
            'author_name' => 'required|max:255',
            'author_url' => 'required|max:255',
            'articleable_type' => 'required',
            'articleable_id' => 'required|numeric'
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $exists = DB::table($request->articleable_type)->exists();
        if (!$exists) {
            return $this->errorClient('Không hỗ trợ loại bài viết này');
        }

        $element = DB::table($request->articleable_type)->where('id', $request->articleable_id)->first();
        if (empty($element)) {
            return $this->errorClient('Không tìm thấy đối tượng');
        }

        $attributes = $request->only([
            'title',
            'author_name',
            'author_url',
            'articleable_type',
            'articleable_id'
        ]);
        $createdImages = [];
        $attributes['content'] = HtmlUtil::minify($request->input('content'), $createdImages);

        try {
            DB::beginTransaction();
            $model = $this->repository->create($attributes);
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
        if (!($model instanceof Article)) {
            return $this->errorNotFound();
        }

        $validator = $this->validateRequest($request, [
            'title' => 'nullable|max:255',
            'content' => 'nullable',
            'author_name' => 'nullable|max:255',
            'author_url' => 'nullable'
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = [];
        $columns = [
            'title',
            'author_name',
            'author_url'
        ];

        foreach ($columns as $column) {
            if ($request->has($column)) {
                $attributes[$column] = $request->input($column);
            }
        }
        $createdImages = [];
        $attributes['content'] = HtmlUtil::minify($request->input('content'), $createdImages);

        try {
            DB::beginTransaction();
            $model = $this->repository->update($model, $attributes);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            FileStorageUtil::getInstance()->deleteFiles($createdImages);
            return $this->error($e->getMessage());
        }
    }

}
