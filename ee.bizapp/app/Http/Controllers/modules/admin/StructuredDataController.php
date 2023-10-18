<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\StructuredData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StructuredDataController extends Controller implements ApiController
{
    public function index(Request $request)
    {
        $structureble_id = $request->input('structureble_id');
        $structureble_type = $request->input('structureble_type');
        $limit = $request->input('limit', null);
        $query = StructuredData::where('structureble_id', $structureble_id)
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
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        $code = $request->input('code');

        try {
            DB::beginTransaction();
            $model = new StructuredData();
            $model->code = $code;
            $model->data = $request->input('data','[]');
            $model->structureble_id = $request->input('structureble_id');
            $model->structureble_type = $request->input('structureble_type');
            $model->save();
            DB::commit();
            Artisan::call('cache:clear');
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function show($id)
    {
        // TODO: Implement show() method.
    }

    public function update(Request $request, $id)
    {
        $model = (new StructuredData())->find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
//        $data = json_decode($request->input('data'));
        $code = $request->input('code');
        try {
            DB::beginTransaction();
            $model->code = $code;
            $model->save();
            DB::commit();
            Artisan::call('cache:clear');
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = StructuredData::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->delete();
        Artisan::call('cache:clear');
        return $this->success($model);
    }
}
