<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\ApiController;
use App\Models\User;
use App\Utils\AuthUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserController extends Controller implements ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search', null);
        $limit = $request->input('limit', null);
        $query = User::when($search, function ($q) use ($search) {
                return $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('username','like', '%' . $search . '%')
                    ->orWhere('phone','like', '%' . $search . '%')
                    ->orWhere('email','like', '%' . $search . '%');
            });
        if ($limit) {
            $data = $query->paginate($limit);
        } else {
            $data = $query->get();
        }
        return $this->success($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $auth = AuthUtil::getInstance()->getModel();
        $newModel = new User();
        $user = User::Where('username', $request->username)->first();
        if($user){
            return $this->error('Tài khoản này đã tồn tại');
        }
        $newModel->name = $request->name;
        $newModel->username = $request->username;
        $newModel->remember_token = Str::random(100);
        $newModel->password = Hash::make($request->input('password'));
        try {
            DB::beginTransaction();
            $newModel->save();
            DB::commit();
            return $this->success($newModel);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = User::find($id);
        $model->name = $request->input('name', null);
        $password = $request->input('password');
        if ($password){
            $model->password = Hash::make($password);
            $model->remember_token = Str::random(100);
        }
        $model->save();
        return $this->success($model);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $auth = AuthUtil::getInstance()->getModel();
        if ($auth->id == $id){
            return $this->error('Tài khoản đang đăng nhập');
        }
        $model = User::find($id);
        $model->delete();
        return $this->success($model);
    }
}
