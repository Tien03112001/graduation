<?php

namespace App\Http\Controllers\modules\super_admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\UserRepositoryInterface;
use App\Repository\AgentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends RestController
{
    protected $agentRepository;
    
    public function __construct(UserRepositoryInterface $repository, AgentRepositoryInterface $agentRepository)
    {
        parent::__construct($repository);
        $this->agentRepository = $agentRepository;
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
            'username' => 'required|max:255',
            'password' => 'required|max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $attributes = $request->only([
            'name',
            'username',
        ]);
        $attributes['password'] = Hash::make($request->input('password', '123456a@'));
        $attributes['remember_token'] = Str::random(100);
        $name_test = $this->repository->find([WhereClause::query('username', $request->input('username'))]);
        if ($name_test) {
            return $this->errorClient('Tên đăng nhập đã tồn tại');
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
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|max:255',
            'username' => 'nullable|max:255',
            'password' => 'nullable|max:255',
        ], $this->validatorMessages);
        if ($validator->fails()) {
            return $this->errorClient($validator->errors()->first());
        }
        $name_test = $this->repository->get([WhereClause::query('username', $request->input('username')),WhereClause::queryDiff('id', $model->id)],[],[],[])->first();
        if ($name_test) {
            return $this->errorClient('Tên đăng nhập đã tồn tại');
        }
        try {
            DB::beginTransaction();
            $attributes = $request->only([
                'name',
                'username',
            ]);
            if ($request->has('password')) {
                $attributes['password'] = Hash::make($request->input('password', '123456a@'));
            }
            if ($request->has('reset_password')) {
                $attributes['password'] = Hash::make('123456a@');
            }
            $attributes['remember_token'] = Str::random(100);
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
        $model = $this->repository->findById($id,['roles']);
        $model->roles()->detach();
        $agent = $this->agentRepository->find([WhereClause::query('user_id',$id)]);
        if($agent) {
            $agent->fanpages()->detach();
        }
        $this->agentRepository->bulkDelete([WhereClause::query('user_id',$id)]);
        return parent::destroyDefault($id);
    }
}
