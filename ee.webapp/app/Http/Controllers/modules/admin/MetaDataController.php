<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\MetaDataRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MetaDataController extends RestController
{

    public function __construct(MetaDataRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'metaable_id' => 'required|numeric',
            'metaable_type' => 'required|exists:meta_data,metaable_type'
        ]);

        if ($validator) {
            return $this->errorClient($validator);
        }

        $clauses = [
            WhereClause::query('metaable_type', $request->metaable_type),
            WhereClause::query('metaable_id', $request->metaable_id),
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
            'metaable_id' => 'required|numeric',
            'metaable_type' => 'required',
            'description' => 'required',
            'keywords' => 'nullable',
            'robots' => 'nullable',
            'canonical' => 'nullable',
            'more' => 'nullable',
            'title' => 'required|max:255'
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $exists = DB::table($request->metaable_type)->exists();
        if (!$exists) {
            return $this->errorClient('Không hỗ trợ loại này');
        }

        $element = DB::table($request->metaable_type)->where('id', $request->metaable_id)
            ->first();
        if (empty($element)) {
            return $this->errorClient('Không tìm thấy đối tượng');
        }

        $attributes = $request->only([
            'metaable_id',
            'metaable_type',
            'description',
            'keywords',
            'robots',
            'canonical',
            'more',
            'title'
        ]);

        try {
            DB::beginTransaction();
            $model = $this->repository->create($attributes);
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
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        return $this->success($model);
    }

    public function update(Request $request, $id)
    {
        return $this->updateDefault($request, $id, [
            'description',
            'keywords',
            'robots',
            'canonical',
            'more',
            'title'
        ], [
            'description' => 'nullable',
            'keywords' => 'nullable',
            'robots' => 'nullable',
            'canonical' => 'nullable',
            'more' => 'nullable',
            'title' => 'nullable|max:255'
        ]);
    }

}
