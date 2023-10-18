<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\GalleryImage;
use App\Repository\GalleryImageRepositoryInterface;
use App\Utils\FileStorageUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GalleryImageController extends RestController
{

    public function __construct(GalleryImageRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'gallery_id' => ['required', 'numeric'],
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $whereClauses = [];
        array_push($whereClauses, WhereClause::query('gallery_id', $request->gallery_id));
        $orderBy = $request->input('orderBy', 'order:asc');

        return $this->indexDefault($request, $whereClauses, [], [], $orderBy);
    }

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'gallery_id' => ['required', 'numeric', 'exists:galleries,id'],
            'image' => 'required|mimetypes:image/*',
            'alt' => 'nullable|max:255'
        ]);

        if ($validator) {
            return $this->errorClient($validator);
        }

        $lastModel = $this->repository->find([
            WhereClause::query('gallery_id', $request->gallery_id),
        ], 'order:desc');

        $createdImages = [];
        $url = FileStorageUtil::getInstance()->putFile('images', $request->file('image'));
        if (!$url) {
            return $this->error('Not Found File');
        }

        array_push($createdImages, $url);

        try {
            DB::beginTransaction();
            $model = $this->repository->create([
                'gallery_id' => $request->gallery_id,
                'path' => $url,
                'alt' => $request->alt,
                'order' => $lastModel ? $lastModel->order + 1 : 0
            ]);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            FileStorageUtil::getInstance()->deleteFiles($createdImages);
            return $this->error($e->getMessage());
        }
    }

    public function show($id)
    {
        return $this->showDefault($id);
    }

    public function update(Request $request, $id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof GalleryImage)) {
            return $this->errorNotFound();
        }

        $validator = $this->validateRequest($request, [
            'image' => 'nullable|mimetypes:image/*',
            'alt' => 'nullable|max:255'
        ]);

        if ($validator) {
            return $this->errorClient($validator);
        }

        $createdImages = [];
        $url = FileStorageUtil::getInstance()->putFile('images', $request->file('image'));
        if (!$url) {
            return $this->error('Not Found File');
        }

        array_push($createdImages, $url);

        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, [
                'path' => $url,
                'alt' => $request->alt
            ]);
            DB::commit();
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
        if (!($model instanceof GalleryImage)) {
            return $this->errorNotFound();
        }
        try {
            DB::beginTransaction();
            FileStorageUtil::getInstance()->deleteFile($model->path);
            $this->repository->delete($model);
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
            return $this->errorClient('Ảnh không tồn tại');
        }
        if (!($model instanceof GalleryImage)) {
            return $this->errorClient('Ảnh không tồn tại');
        }

        $swapModel = $this->repository->find([
            WhereClause::query('order', $model->order, '<'),
            WhereClause::query('gallery_id', $model->gallery_id),
        ]);

        if (empty($swapModel)) {
            return $this->errorClient('Ảnh tăng thứ hạng');
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
            return $this->errorClient('Ảnh không tồn tại');
        }
        if (!($model instanceof GalleryImage)) {
            return $this->errorClient('Ảnh không tồn tại');
        }

        $swapModel = $this->repository->find([
            WhereClause::query('order', $model->order, '>'),
            WhereClause::query('gallery_id', $model->gallery_id),
        ]);

        if (empty($swapModel)) {
            return $this->errorClient('Ảnh thể giảm thứ hạng');
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
