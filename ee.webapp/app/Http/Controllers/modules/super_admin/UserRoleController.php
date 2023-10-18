<?php

namespace App\Http\Controllers\modules\super_admin;

use App\Http\Controllers\RestController;
use App\Common\WhereClause;
use App\Repository\UserRoleRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Repository\RoleRepositoryInterface;
use App\Repository\AgentRepositoryInterface;
use App\Utils\RedisUtil;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class UserRoleController extends RestController
{
    protected $userRepository;
    protected $roleRepository;
    protected $agentRepository;

    public function __construct(UserRoleRepositoryInterface $repository, UserRepositoryInterface $userRepository, RoleRepositoryInterface $roleRepository, AgentRepositoryInterface $agentRepository)
    {
        parent::__construct($repository);
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->agentRepository = $agentRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $orderBy = [];
        $with = ['user_role'];
        $withCount = [];
        if ($request->has('user_id')) {
            array_push($clauses, WhereClause::query('user_id', $request->user_id));
        }
        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->repository->get($clauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }

    public function attachUserRole(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'user_id' => 'required|max:255',
            'role_id' => 'required|max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $user = $this->userRepository->findById($request->input('user_id'));
        if (empty($user)) {
            return $this->errorClient('ID người dùng không tồn tại');
        }
        $role = $this->roleRepository->findById($request->input('role_id'));
        if (empty($role)) {
            return $this->errorClient('Role không tồn tại');
        }
        try {
            DB::beginTransaction();
            $model = $this->userRepository->attach($user, $role);
            if($role->name == 'Tư vấn viên') {
                $attributes['id'] = $request->input('user_id');
                $attributes['user_id'] = $request->input('user_id');
                $attributes['user_name'] = $user->name;
                $this->agentRepository->create($attributes);
                RedisUtil::set('agents:'.$user->remember_token,'1','chatapp');
            }
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function detachUserRole(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'user_id' => 'required|max:255',
            'role_id' => 'required|max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $user = $this->userRepository->findById($request->input('user_id'));
        if (empty($user)) {
            return $this->errorClient('ID người dùng không tồn tại');
        }
        $role = $this->roleRepository->findById($request->input('role_id'));
        if (empty($role)) {
            return $this->errorClient('Role không tồn tại');
        }
        try {
            DB::beginTransaction();
            $model = $this->userRepository->detach($user, $role);
            if($role->name == 'Tư vấn viên') {
                $agent = $this->agentRepository->find([WhereClause::query('user_id',$request->input('user_id'))]);
                if($agent) {
                    $agent->fanpages()->detach();
                }
                $this->agentRepository->bulkDelete([WhereClause::query('user_id',$request->input('user_id'))]);
            }
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}
