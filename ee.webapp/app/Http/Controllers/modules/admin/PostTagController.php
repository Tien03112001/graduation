<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\MetaData;
use App\Models\PostTag;
use App\Repository\PostTagRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PostTagController extends RestController
{

    public function __construct(PostTagRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $whereClauses = [];
        if ($request->has('search')) {
            array_push($whereClauses, WhereClause::queryLike('name', $request->search));
        }
        $orderBy = $request->input('orderBy', 'id:asc');

        return $this->indexDefault($request, $whereClauses, ['meta', 'article'], [], $orderBy);
    }

    public function store(Request $request)
    {
        $title = $request->input('name');
        $slug = $request->input('slug', Str::slug($title));

        $model = new PostTag();
        $model->name = $title;
        $model->slug = $slug;

        $meta = new MetaData();
        $meta->title = $title;
        $meta->keywords = $request->input('meta_keywords');
        $meta->robots = $request->input('meta_robots');
        $meta->canonical = $request->input('meta_canonical');
        $meta->more = $request->input('other_code');
        $meta->description = $request->input('meta_description', 'Tag: ' . $title);
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

        $model = (new PostTag())->find($id);
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
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof PostTag)) {
            return $this->errorNotFound();
        }
        try {
            DB::beginTransaction();
            $model->posts()->detach();
            $model->delete();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}
