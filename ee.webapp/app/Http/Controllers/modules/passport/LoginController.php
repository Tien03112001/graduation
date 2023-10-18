<?php

namespace App\Http\Controllers\modules\passport;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\User;
use App\Repository\UserRepositoryInterface;
use App\Utils\CryptUtil;
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
        ], null, ['roles']);
        if (empty($user) || !($user instanceof User)) {
            return $this->errorClient('Tài khoản không đúng');
        }
        if (!Hash::check($password, $user->password)) {
            return $this->errorClient('Mật khẩu không đúng');
        }

        $roles = [];
        foreach ($user->roles as $role) {
            array_push($roles, $role->name);
        }

        $data = [
            'id' => $user->id,
            'username' => $user->username,
            'name' => $user->name,
            'roles' => $roles,
            'expired_at' => now()->addDays(7)
        ];

        return $this->success([
            'token' => CryptUtil::getInstance()->encryptData($data),
            'name' => $user->name,
        ]);
    }

}
