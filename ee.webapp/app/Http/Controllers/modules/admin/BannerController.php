<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\Banner;
use App\Repository\BannerRepositoryInterface;
use App\Utils\FileStorageUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BannerController extends RestController
{

    public function __construct(BannerRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'group_id' => ['required', 'numeric'],
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $whereClauses = [];

        if ($request->has('search')) {
            array_push($whereClauses, WhereClause::queryLike('name', $request->search));
        }

        array_push($whereClauses, WhereClause::query('group_id', $request->group_id));
        $orderBy = $request->input('orderBy', 'order:asc');

        return $this->indexDefault($request, $whereClauses, [], [], $orderBy);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_id' => 'required'
        ], ['group_id.required' => 'Nhóm banner không được để trống']);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }

        // $createdImages = [];
        // if ($request->hasFile('image')) {
        //     $url = FileStorageUtil::getInstance()->getInstance()->putFile('images', $request->file('image'));
        //     if (!($url)) {
        //         return $this->error('Not Found File');
        //     }
        //     array_push($createdImages, $url);
        // } else {
        //     return $this->error('Chưa upload ảnh');
        // }
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $filename = 'http://localhost:8000/storage/'.time() . '.' . $extenstion;

            $file->move('storage', $filename);
            $attributes['image'] = $filename;
        }
        $model = new Banner();
        $model->name = $request->input('name');
        $model->group_id = $request->input('group_id');
        $model->href = $request->input('href', null);
        $model->summary = $request->input('summary', null);
        $model->image = $filename;
        $model->alt = $request->input('alt', $model->name);
        $model->order = 0;
        $lastBanner = $this->repository->find([
            WhereClause::query('group_id', $request->input('group_id'))
        ], 'order:desc');
        if (isset($lastBanner)) {
            $model->order = $lastBanner->order + 1;
        }
        try {
            DB::beginTransaction();
            $model->save();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            FileStorageUtil::getInstance()->deleteFiles($createdImages);
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $model = (new Banner())->find($id);
        $model->name = $request->input('name');
        $model->group_id = $request->input('group_id');
        $model->href = $request->input('href', null);
        $model->summary = $request->input('summary', null);
        $model->alt = $request->input('alt', $model->name);

        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $filename = 'http://localhost:8000/storage/'.time() . '.' . $extenstion;

            $file->move('storage', $filename);
            $model->image = $filename;
        }

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
        if (!($model instanceof Banner)) {
            return $this->errorNotFound();
        }
        try {
            DB::beginTransaction();
            FileStorageUtil::getInstance()->deleteFile($model->image);
            $this->repository->delete($model);
            DB::commit();
            return $this->success([]);
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
            return $this->errorClient('Banner không tồn tại');
        }

        $swapModel = $this->repository->find([WhereClause::query('order', $model->order, '<')]);

        if (empty($swapModel)) {
            return $this->errorClient('Banner tăng thứ hạng');
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
            return $this->errorClient('Banner không tồn tại');
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
