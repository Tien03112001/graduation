<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\ApiController;
use App\Models\User;
use App\Utils\AuthUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function get()
    {
        $data = AuthUtil::getInstance()->getModel();
        return response()->json(compact('data'), 200);
    }

    public function update(Request $request)
    {
        $params = $request->all();
        $auth = AuthUtil::getInstance()->getModel();
        $user = User::find($auth->id);
        $user->name = $request->input('name');
        $password = $request->input('password', null);
        if ($password) {
            $check = Hash::check($params['oldpassword'], $user->password);
            if (!$check) {
                return response()->json(['message' => 'old password error'], 500);
            }
            $user->password = Hash::make($password);
        }
        $user->save();
        return response()->json(['message' => 'success'], 200);
    }
}
