<?php

namespace App\Http\Controllers\modules\admin;

use App\Models\SettingLanguage;
use App\Utils\CacheUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SettingLanguageController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', null);
        $limit = $request->input('limit', null);
        $query = SettingLanguage::when($search, function ($q) use ($search) {
            return $q->where('name', 'like', '%' . $search . '%')->orWhere('translate', 'like', '%' . $search . '%');
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
        try {
            DB::beginTransaction();
            $model = new SettingLanguage();
            $model->name = $request->input('name');
            $model->translate = $request->input('translate');;
            $model->save();
            DB::commit();
            CacheUtil::reloadLanguage();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $model = (new SettingLanguage())->find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        try {
            DB::beginTransaction();
            $model->name = $request->input('name', $model->name);
            $model->translate = $request->input('translate', $model->translate);
            $model->save();
            DB::commit();
            CacheUtil::reloadLanguage();
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
        $model = SettingLanguage::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->delete();
        CacheUtil::reloadLanguage();
        Artisan::call('cache:clear');
        return $this->success($model);
    }
}
