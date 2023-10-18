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

class PhotoController extends RestController
{

    public function __construct(GalleryImageRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $clauses = [
            WhereClause::query('gallery_id', 0)
        ];
        if ($request->has('search')) {
            array_push($clauses, WhereClause::queryLike('alt', $request->search));
        }

        $orderBy = $request->input('orderBy', 'id:desc');

        $limit = $request->input('limit');
        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy);
        } else {
            $data = $this->repository->get($clauses, $orderBy);
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'image' => 'required|mimetypes:image/*',
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
            $model = $this->repository->create([
                'gallery_id' => 0,
                'path' => $url,
                'alt' => $request->alt,
                'order' => 0
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


}
