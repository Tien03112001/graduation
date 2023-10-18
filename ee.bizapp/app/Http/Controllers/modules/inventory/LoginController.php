<?php

namespace App\Http\Controllers\modules\inventory;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $user = Account::where('username', $username)->with('roles')->first();

        if (empty($user)) {
            return $this->error('Tài khoản không đúng');
        }

        foreach ($user->roles as $r) {
            if ($r->name == 'Inventory') {
                if (!Hash::check($password, $user->password)) {
                    return $this->error('Mật khẩu không đúng');
                }
                return $this->success(['token' => $user->remember_token, 'name' => $user->name]);
            }
        }
        return $this->error('Không phải tài khoản inventory');
    }
}
