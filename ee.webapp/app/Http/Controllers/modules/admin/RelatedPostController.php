<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\PostRepositoryInterface;
use App\Repository\RelatedPostRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RelatedPostController extends RestController
{
    protected $postRepository;

    public function __construct(RelatedPostRepositoryInterface $repository,
                                PostRepositoryInterface $postRepository)
    {
        parent::__construct($repository);
        $this->postRepository = $postRepository;
    }

    public function index(Request $request)
    {
        $whereClauses = [];
        if ($request->has('post_id')) {
            array_push($whereClauses, WhereClause::query('post_id', $request->post_id));
        }
        $orderBy = $request->input('orderBy', 'order:asc');

        return $this->indexDefault($request, $whereClauses, ['post', 'relation'], [], $orderBy);
    }

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'post_id' => ['required', 'numeric'],
            'related_id' => ['required', 'numeric'],
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $product = $this->postRepository->findById($request->post_id);
        if (empty($product)) {
            return $this->errorClient('Bài viết không tồn tại');
        }
        $relatedProduct = $this->postRepository->findById($request->related_id);
        if (empty($relatedProduct)) {
            return $this->errorClient('Bài viết liên quan không tồn tại');
        }

        $attributes = $request->only([
            'post_id',
            'related_id',
        ]);

        $attributes['order'] = 0;

        $lastRelated = $this->repository->find([
            WhereClause::query('post_id', $request->post_id)
        ], 'order:desc');
        if (isset($lastRelated)) {
            $attributes['order'] = $lastRelated->order + 1;
        }

        try {
            DB::beginTransaction();
            $model = $this->repository->create($attributes, ['post', 'relation']);
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
        return $this->destroyDefault($id);
    }

    public function up($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorClient('ID không tồn tại');
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
            return $this->errorClient('ID không tồn tại');
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
