<?php

namespace App\Http\Controllers\modules\super_admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FormController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', null);
        $limit = $request->input('limit', null);
        $query = (new Form())->with('attributes');
        if ($limit) {
            $data = $query->paginate($limit);
        } else {
            $data = $query->get();
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ], [
            'name.required' => 'Tên không được để trống',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }
        try {
            DB::beginTransaction();
            $model = new Form();
            $model->name = $request->input('name');
            $model->description = $request->input('description');
            $model->save();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

}
