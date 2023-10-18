<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\Controller;
use App\Models\StructureData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StructureDataController extends Controller
{
    public function index(Request $request)
    {
        $structureble_id = $request->input('structureble_id');
        $structureble_type = $request->input('structureble_type');
        $limit = $request->input('limit', null);
        $query = StructureData::where('structureble_id', $structureble_id)
            ->where('structureble_type', $structureble_type);
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
            'code' => 'required',
            'content' => 'required'
        ], [
            'code.required' => 'Mã code không được để trống',
            'content.required' => 'Nội dung không được để trống'
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }
        $code = $request->input('code');

        try {
            DB::beginTransaction();
            $model = new StructureData();
            $model->code = $code;
            $model->content = $request->input('content');
            $model->structureble_id = $request->input('structureble_id');
            $model->structureble_type = $request->input('structureble_type');
            $model->save();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'content' => 'required'
        ], [
            'code.required' => 'Mã code không được để trống',
            'content.required' => 'Nội dung không được để trống'
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }
        $model = (new StructureData())->find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $code = $request->input('code');
        try {
            DB::beginTransaction();
            $model->code = $code;
            $model->content = $request->input('content');
            $model->save();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = StructureData::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->delete();
        return $this->success($model);
    }
}
