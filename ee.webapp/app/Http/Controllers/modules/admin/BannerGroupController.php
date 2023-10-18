<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\BannerGroup;
use App\Repository\BannerGroupRepositoryInterface;
use App\Repository\BannerRepositoryInterface;
use App\Utils\FileStorageUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BannerGroupController extends RestController
{
    protected $bannerRepository;

    public function __construct(BannerGroupRepositoryInterface $repository, BannerRepositoryInterface $bannerRepository)
    {
        parent::__construct($repository);
        $this->bannerRepository = $bannerRepository;
    }

    public function index(Request $request)
    {
        $clauses = [];
        if ($request->has('search')) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        }
        $orderBy = $request->input('orderBy', 'id:asc');

        $limit = $request->input('limit', null);
        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, ['banners']);
        } else {
            $data = $this->repository->get($clauses, $orderBy, ['banners']);
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {

        $validator = $this->validateRequest($request, [
            'name' => 'required|max:255|unique:banner_groups,name',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $attributes = [
            'name' => $request->name,
            'description' => $request->description,
            'code' => Str::slug($request->name)
        ];
        try {
            DB::beginTransaction();
            $model = $this->repository->create($attributes, ['banners']);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
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
        if (!($model instanceof BannerGroup)) {
            return $this->errorNotFound();
        }
        $validator = $this->validateRequest($request, [
            'name' => ['required', Rule::unique('banner_groups', 'name')->ignore($id)],
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $attributes = [
            'name' => $request->name,
            'code' => Str::slug($request->name)
        ];
        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, $attributes, ['banners']);
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
        $model = $this->repository->findById($id, ['banners']);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof BannerGroup)) {
            return $this->errorNotFound();
        }

        try {
            DB::beginTransaction();
            foreach ($model->banners as $banner) {
                $this->bannerRepository->delete($banner);
                FileStorageUtil::getInstance()->deleteFile($banner->image);
            }
            $this->repository->delete($model);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }

    }
}
