<?php

namespace App\Http\Controllers\modules\chat;

use App\Common\WhereClause;
use Illuminate\Http\Request;
use App\Repository\AgentAccountRepositoryInterface;
use App\Http\Controllers\RestController;
use App\Models\Agent;
use App\Repository\AgentRepositoryInterface;
use App\Utils\AuthUtil;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AgentController extends RestController
{
    protected  $accountRepository;
    public function __construct(AgentRepositoryInterface $repository, AgentAccountRepositoryInterface $accountRepository)
    {
        parent::__construct($repository);
        $this->accountRepository = $accountRepository;
    }

    public function online()
    {
        $agent = AuthUtil::getInstance()->getModel();
        $model = $this->repository->findById($agent->id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof Agent)) {
            return $this->errorNotFound();
        }

        $attributes = [
            'is_online' => 1,
            'online_at' => now()->toDateTimeString()
        ];

        try {
            DB::beginTransaction();
            $model = $this->repository->update($model, $attributes);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function offline()
    {
        $agent = AuthUtil::getInstance()->getModel();
        $model = $this->repository->findById($agent->id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof Agent)) {
            return $this->errorNotFound();
        }

        $attributes = [
            'is_online' => 0,
            'online_at' => null
        ];

        try {
            DB::beginTransaction();
            $model = $this->repository->update($model, $attributes);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function checkAgent(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'token' => 'required',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $user = $this->accountRepository->find([WhereClause::query('remember_token', $request->token)]);
        if (empty($user)) {
            return $this->errorClient('Người dùng không đúng');
        }
        $agent = $this->repository->findById($user->id);
        if (empty($agent)) {
            return $this->errorClient('Không phải tài khoản tư vấn viên');
        }
        return $this->success($agent);
    }
}
