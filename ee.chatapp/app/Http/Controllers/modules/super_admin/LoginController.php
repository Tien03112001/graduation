<?php

namespace App\Http\Controllers\modules\super_admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\User;
use App\Repository\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends RestController
{

    public function __construct(UserRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $user = $this->repository->find([
            WhereClause::query('username', $username)
        ]);
        if (empty($user)) {
            return $this->error('Tài khoản không đúng');
        }
        if (!($user instanceof User)) {
            return $this->errorNotFound();
        }

        if (!Hash::check($password, $user->password)) {
            return $this->error('Mật khẩu không đúng');
        }
        return $this->success(['token' => $user->remember_token, 'name' => $user->name]);
    }
}
