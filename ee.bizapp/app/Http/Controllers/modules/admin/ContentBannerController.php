<?php

namespace App\Http\Controllers\modules\admin;


use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\ContentBanner;
use App\Utils\FileUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ContentBannerController extends Controller implements ApiController
{
    public function index(Request $request)
    {
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content_banner_group_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        if($request->hasFile('image')){
            $url = Storage::disk('public')->put('images', $request->file('image'));
            if (empty($url)) {
                return $this->error('Not Found File');
            }
        }else{
            return $this->error('Chưa upload ảnh');
        }


        $model = new ContentBanner();
        $model->name = $request->input('name');
        $model->priority = $request->input('priority');
        $model->content_banner_group_id = $request->input('content_banner_group_id');
        $model->href = $request->input('href', null);
        $model->summary = $request->input('summary', null);
        $model->image = $url;
        $model->alt = $request->input('alt', $model->name);
        try {
            DB::beginTransaction();
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
    }

    public function update(Request $request, $id)
    {
        $model = (new ContentBanner())->find($id);
        $model->name = $request->input('name');
        $model->content_banner_group_id = $request->input('content_banner_group_id');
        $model->href = $request->input('href', null);
        $model->summary = $request->input('summary', null);

        $model->alt = $request->input('alt', $model->name);

        if ($request->hasFile('image')) {
            $url = Storage::disk('public')->put('images', $request->file('image'));
            if (empty($url)) {
                return $this->error('Not Found File');
            }
            FileUtil::removePublicFile($model->image);
            $model->image = $url;
        }

        try {
            DB::beginTransaction();
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

    public function update_priority(Request $request, $id)
    {
        $model = (new ContentBanner())->find($id);
        $model->priority = $request->input('priority');
        try {
            DB::beginTransaction();
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
        $model = ContentBanner::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        FileUtil::removePublicFile($model->image);
        $model->delete();
        Artisan::call('cache:clear');
        return $this->success($model);
    }

}
