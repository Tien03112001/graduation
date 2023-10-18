<?php

namespace App\Http\Controllers\modules\super_admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\SystemUser;
use App\Repository\SystemUserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends RestController
{

    public function __construct(SystemUserRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function login(Request $request)
    {

        $validator = $this->validateRequest($request, [
            'username' => 'required|max:255',
            'password' => 'required|max:255'
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $username = $request->input('username');
        $password = $request->input('password');

        $user = $this->repository->find([
            WhereClause::query('username', $username),
        ]);
        if (empty($user) && !($user instanceof SystemUser)) {
            return $this->errorClient('Tài khoản không đúng');
        }
        if (!Hash::check($password, $user->password)) {
            return $this->errorClient('Mật khẩu không đúng');
        }
        return $this->success([
            'authToken' => $user->remember_token,
            'name' => $user->name,
        ]);
    }

}
