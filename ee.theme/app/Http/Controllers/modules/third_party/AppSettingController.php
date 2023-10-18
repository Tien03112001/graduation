<?php

namespace App\Http\Controllers\modules\third_party;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\AppSettingRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppSettingController extends RestController
{
    public function __construct(AppSettingRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $orderBy = $request->input('orderBy', 'id:asc');

        if ($request->has('search')) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        }

        $with = [];
        $withCount = [];

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->repository->get($clauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'name' => 'required|max:255',
            'value' => 'required',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $attributes = $request->only([
            'name',
            'value',
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

    public function destroy($id)
    {
        return parent::destroyDefault($id);
    }

}
