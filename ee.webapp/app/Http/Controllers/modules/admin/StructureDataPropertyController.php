<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\RestController;
use Illuminate\Http\Request;
use App\Common\WhereClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Utils\FileStorageUtil;
use App\Models\StructureDataProperty;
use App\Repository\StructureDataPropertyRepositoryInterface;

class StructureDataPropertyController extends RestController
{
    public function __construct(StructureDataPropertyRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $whereClauses = [];
        $orderBy = $request->input('orderBy', 'id:desc');
        if ($request->has('search')) {
            array_push($whereClauses, WhereClause::query('name', $request->search));
        }
        if ($request->has('type_id')) {
            array_push($whereClauses, WhereClause::query('type_id', $request->type_id));
        }
        return $this->indexDefault($request, $whereClauses, [], [], $orderBy);
    }

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'type_id' => ['required'],
            'name' => ['required'],
            'value_type' => ['required'],
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $attributes = $request->only([
            'type_id',
            'name',
            'value_type',
            'value',
            'possible_values'
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

    public function update(Request $request, $id)
    {
        $file_old = '';
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof StructureDataProperty)) {
            return $this->errorNotFound();
        }
        $validator = $this->validateRequest($request, [
            'name' => ['nullable'],
            'value' => ['nullable'],
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $attributes['name'] = $request->name;

        if ($model->value_type == 'file' && $request->file('value') != '') {
            $createdFiles = [];
            $file = FileStorageUtil::getInstance()->putFile('file_store', $request->file('value'));
            if (!$file) {
                return $this->error('Not Found File');
            }
            if ($model->value != null) {
                $file_old = $model->value;
            }
            array_push($createdFiles, $file);
            $attributes['value'] = $file;
        }
        if ($model->value_type != 'file') {
            $attributes['value'] = $request->value;
        }
        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, $attributes);
            DB::commit();
            if ($model->value_type == 'file' && $file_old != '') {
                FileStorageUtil::getInstance()->deleteFile($file_old);
            }
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            if ($model->value_type == 'file') {
                FileStorageUtil::getInstance()->deleteFile($attributes['value']);
            }
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof StructureDataProperty)) {
            return $this->errorNotFound();
        }
        try {
            DB::beginTransaction();
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
