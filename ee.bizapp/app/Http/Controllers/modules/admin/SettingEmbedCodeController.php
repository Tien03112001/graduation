<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\SettingEmbedCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SettingEmbedCodeController extends Controller implements ApiController
{
    public function index(Request $request)
    {
        $search = $request->input('search', null);
        $limit = $request->input('limit', null);
        $query = SettingEmbedCode::when($search, function ($q) use ($search) {
            return $q->where('name', 'like', '%' . $search . '%');
        });
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
            'name' => 'required',
            'type' => 'required',
            'code' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        try {
            DB::beginTransaction();
            $model = new SettingEmbedCode();
            $model->name = $request->input('name');
            $model->type = $request->input('type');
            $model->priority = $request->input('priority', 0);
            $model->code = $request->input('code');
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
        $model = (new SettingEmbedCode())->find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        try {
            DB::beginTransaction();
            $model->name = $request->input('name', $model->name);
            $model->type = $request->input('type', $model->type);
            $model->priority = $request->input('priority', $model->priority);
            $model->code = $request->input('code', $model->code);
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
        $model = SettingEmbedCode::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->delete();
        Artisan::call('cache:clear');
        return $this->success($model);
    }
}
