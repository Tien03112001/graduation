<?php

namespace App\Http\Controllers\modules\super_admin;

use App\Http\Controllers\Controller;
use App\Models\FormAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FormAttributeController extends Controller
{
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
            $model = new FormAttribute();
            $model->name = $request->input('name');
            $model->form_id = $request->input('form_id');
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
