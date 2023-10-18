<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\AgentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AgentController extends RestController
{
    public function __construct(AgentRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $orderBy = $request->input('orderBy', 'id:asc');

        if ($request->has('search') && Str::length($request->search) > 0) {
            array_push($clauses, WhereClause::queryLike('user_name', $request->search));
        }
        if ($request->has('search') && Str::length($request->search) == 0) {
            $data = '';
            return $this->success($data);
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
            'user_id' => 'required|max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $attributes = $request->only([
            'user_id',
        ]);
        $attributes['id'] = $request->input('id');
        $attributes['is_online'] = false;
        $attributes['priority'] = 0;
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
