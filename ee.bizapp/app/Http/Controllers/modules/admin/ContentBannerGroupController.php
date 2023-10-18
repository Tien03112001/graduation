<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\ContentBannerGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContentBannerGroupController extends Controller implements ApiController
{
    public function index(Request $request)
    {
        $search = $request->input('search', null);
        $limit = $request->input('limit', null);
        $query = (new ContentBannerGroup())
            ->with(['banners' => function ($q) {
                $q->orderBy('priority', 'asc');
            }]);
        if ($limit) {
            $data = $query->paginate($limit);
        } else {
            $data = $query->get();
        }
        return $this->success($data);
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $model = new ContentBannerGroup();
        $model->name = $request->input('name');
        $model->code = $request->input('code');
        $model->description = $request->input('description');
        try {
            DB::beginTransaction();
            $model->save();
            DB::commit();
            Artisan::call('cache:clear');
            $model->load(['banners']);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function show($id)
    {
    }

    public function update(Request $request, $id)
    {
        $model = (new ContentBannerGroup())->find($id);
        $model->name = $request->input('name');
        $model->description = $request->input('description');
        try {
            DB::beginTransaction();
            $model->save();
            DB::commit();
            Artisan::call('cache:clear');
            $model->load(['banners']);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = ContentBannerGroup::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->banners()->delete();
        $model->delete();
        Artisan::call('cache:clear');

        return $this->success($model);
    }
}
