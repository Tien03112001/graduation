<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\SettingMenuPosition;
use App\Utils\CacheUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SettingMenuPositionController extends Controller implements ApiController
{
    public function index(Request $request)
    {
        $search = $request->input('search', null);
        $limit = $request->input('limit', null);
        $query = SettingMenuPosition::when($search, function ($q) use ($search) {
            return $q->where('name', 'like', '%' . $search . '%');
        })->with(['menus' => function ($q) {
            $q->with(['menuable', 'parent'])->orderBy('order', 'ASC');
        }]);
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
            $model = new SettingMenuPosition();
            $model->name = $request->input('name');
            $model->code = $request->input('code',Str::slug($model->name));
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

    public function show($id)
    {
        // TODO: Implement show() method.
    }

    public function update(Request $request, $id)
    {
        $model = (new SettingMenuPosition())->find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        try {
            DB::beginTransaction();
            $model->name = $request->input('name');
            $model->code = $request->input('code');
            $model->description = $request->input('description');
            $model->save();
            DB::commit();
            CacheUtil::reloadMenu();
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
        $model = SettingMenuPosition::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->menus()->delete();
        $model->delete();
        CacheUtil::reloadMenu();
        Artisan::call('cache:clear');
        return $this->success($model);
    }
}
