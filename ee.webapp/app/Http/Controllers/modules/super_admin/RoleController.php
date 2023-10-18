<?php

namespace App\Http\Controllers\modules\super_admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\Role;
use App\Repository\RoleRepositoryInterface;
use App\Repository\AgentPageRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Repository\AgentRepositoryInterface;

class RoleController extends RestController
{
    protected $agentRepository;
    protected $agentpageRepository;

    public function __construct(RoleRepositoryInterface $repository, AgentRepositoryInterface $agentRepository, AgentPageRepositoryInterface $agentpageRepository)
    {
        parent::__construct($repository);
        $this->agentRepository = $agentRepository;
        $this->agentpageRepository = $agentpageRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $with = [];
        $withCount = [];
        $orderBy = $request->input('orderBy', 'id:desc');

        if ($request->has('search') && Str::length($request->search) > 0) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        }

        if ($request->has('search') && Str::length($request->search) == 0) {
            $data = '';
            return $this->success($data);
        }

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
            'description' => 'max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $attributes = $request->only([
            'name',
            'description',
        ]);
        $name_test = $this->repository->find([WhereClause::query('name', $request->input('name'))]);
        if ($name_test) {
            return $this->errorClient('Tên role đã tồn tại');
        }
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
        if (!($model instanceof Role)) {
            return $this->errorNotFound();
        }

        $validator = $this->validateRequest($request, [
            'name' => 'nullable|max:255',
            'description' => 'nullable|max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $columns = [
            'name',
            'description',
        ];
        $attributes = [];

        $name_test = $this->repository->get([WhereClause::query('name', $request->input('name')),WhereClause::queryDiff('id', $model->id)],[],[],[])->first();
        if ($name_test) {
            return $this->errorClient('Tên role đã tồn tại');
        }

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
        $model = $this->repository->findById($id,['users']);
        if($model->name == 'Tư vấn viên') {
            $this->agentRepository->truncate();
            $this->agentpageRepository->truncate();
        }
        $model->users()->detach();
        return parent::destroyDefault($id);
    }

}
