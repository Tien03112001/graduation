<?php

namespace App\Http\Controllers\modules\chat;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\AgentAccountRepositoryInterface;
use App\Repository\AgentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends RestController
{
    protected $agentRepository;
    public function __construct(AgentAccountRepositoryInterface $repository, AgentRepositoryInterface $agentRepository)
    {
        parent::__construct($repository);
        $this->agentRepository = $agentRepository;
    }

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $user = $this->repository->find([
            WhereClause::query('username', $username)
        ]);
        if (empty($user)) {
            return $this->errorClient('Tài khoản không đúng');
        }
        if (!Hash::check($password, $user->password)) {
            return $this->errorClient('Mật khẩu không đúng');
        }

        $agent = $this->agentRepository->findById($user->id);
        if (empty($agent)) {
            return $this->errorClient('Không phải tài khoản tư vấn viên');
        }

        return $this->success(['token' => $user->remember_token, 'name' => $user->name]);
    }
}
