<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\SettingDefault;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SettingDefaultController extends Controller implements ApiController
{
    public function index(Request $request)
    {
        $search = $request->input('search', null);
        $limit = $request->input('limit', null);
        $query = SettingDefault::when($search, function ($q) use ($search) {
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
            'name' => 'required|unique:setting_defaults|max:255',
            'value' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        try {
            DB::beginTransaction();
            $model = new SettingDefault();
            $model->name = $request->input('name');
            $model->value = $request->input('value');;
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
        $model = (new SettingDefault())->find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        try {
            DB::beginTransaction();
            $model->value = $request->input('value', $model->value);
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
        $model = SettingDefault::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->delete();
        Artisan::call('cache:clear');
        return $this->success($model);
    }
}
