<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\ContentCompanyBranch;
use App\Utils\FileUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ContentCompanyBranchController extends Controller implements ApiController
{
    public function index(Request $request)
    {
        $search = $request->input('search', null);
        $limit = $request->input('limit', null);
        $query = (new ContentCompanyBranch())
            ->when($search, function ($q) use ($search) {
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
        try {
            DB::beginTransaction();
            $model = new ContentCompanyBranch();
            $model->name = $request->input('name');
            $model->address = $request->input('address');
            $model->hotline = $request->input('hotline');
            $model->map = $request->input('map');
            if ($request->hasFile('image')) {
                $url = Storage::disk('public')->put('images', $request->file('image'));
                if (empty($url)) {
                    return $this->error('Not Found File');
                }
                $model->image = $url;
                $model->alt = $request->input('alt', $model->name);
            }
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
        $model = ContentCompanyBranch::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        return $this->success($model);
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $model = (new ContentCompanyBranch())->find($id);
            $model->name = $request->input('name');
            $model->address = $request->input('address');
            $model->hotline = $request->input('hotline');
            $model->map = $request->input('map');
            if ($request->hasFile('image')) {
                $url = Storage::disk('public')->put('images', $request->file('image'));
                if (empty($url)) {
                    return $this->error('Not Found File');
                }
                FileUtil::removePublicFile($model->image);
                $model->image = $url;
                $model->alt = $request->input('alt', $model->name);
            }
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
        $model = ContentCompanyBranch::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        FileUtil::removePublicFile($model->image);
        $model->delete();
        return $this->success($model);
    }
}
