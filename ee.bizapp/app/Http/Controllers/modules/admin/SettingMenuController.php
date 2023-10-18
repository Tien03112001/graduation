<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\SettingMenu;
use App\Utils\CacheUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingMenuController extends Controller implements ApiController
{
    public function index(Request $request)
    {
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $model = new SettingMenu();
            $model->name = $request->input('name');
            $model->url = $request->input('url');
            $model->order = $request->input('order');
            $model->parent_id = $request->input('parent')[0]['id'] ?? 0;
            $model->menuable_id = $request->input('menuable_id')!=0?$request->input('menuable_id'):null;
            $model->menuable_type = $request->input('menuable_id')!=0?$request->input('menuable_type'):null;
            $model->menu_position_id = $request->input('menu_position_id');
            $model->save();
            $model->menuable;
            DB::commit();
            CacheUtil::reloadMenu();
            Artisan::call('cache:clear');
            return $this->success($model->load('parent'));
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
        $model = (new SettingMenu())->find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        try {
            DB::beginTransaction();
            $model->name = $request->input('name', $model->name);
            $model->order = $request->input('order');
            $model->url = $request->input('url');
            $model->save();
            $model->parent;
            $model->menuable;
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
        $model = SettingMenu::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->children()->delete();
        $model->delete();
        CacheUtil::reloadMenu();
        Artisan::call('cache:clear');
        return $this->success($model);
    }
}
