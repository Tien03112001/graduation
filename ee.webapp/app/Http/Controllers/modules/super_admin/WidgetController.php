<?php

namespace App\Http\Controllers\modules\super_admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\Widget;
use App\Repository\WidgetRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WidgetController extends RestController
{

    public function __construct(WidgetRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $whereClauses = [];
        if ($request->has('search')) {
            array_push($whereClauses, WhereClause::queryLike('name', $request->search));
        }

        return $this->indexDefault($request, $whereClauses);
    }

    public function show($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        $model->append([
            'view_html'
        ]);
        return $this->success($model);
    }


    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'name' => 'required|max:255',
            'html' => 'required'
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'name',
            'description',
            'html',
            'css',
            'js',
        ]);

        $attributes['is_system'] = true;

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
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof Widget)) {
            return $this->errorNotFound();
        }
        $validator = $this->validateRequest($request, [
            'name' => 'nullable|max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $columns = [
            'name',
            'description',
            'html',
            'css',
            'js',
        ];

        $attributes = [];

        foreach ($columns as $column) {
            $attributes[$column] = $request->{$column};
        }


        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, $attributes);
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
        if (!($model instanceof Widget)) {
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
