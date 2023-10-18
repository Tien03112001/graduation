<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\SeoMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SeoMetaController extends Controller implements ApiController
{
    public function index(Request $request)
    {
        $model = SeoMeta::where('metaable_id', $request->input('id'))
            ->where('metaable_type', $request->input('type'))->first();
        if (empty($model)) {
            return $this->error('Not found');
        }
        return $this->success($model);
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
        $model = SeoMeta::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        return $this->success($model);
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $model = (new SeoMeta())->find($id);
            $model->description = $request->input('description');
            $model->keywords = $request->input('keywords');
            $model->robots = $request->input('robots');
            $model->canonical = $request->input('canonical');
            $model->more = $request->input('more');
            $model->title = $request->input('title');
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
    }
}
