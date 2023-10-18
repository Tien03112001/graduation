<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\ContentPhoto;
use App\Utils\FileUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ContentPhotoController extends Controller implements ApiController
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', '');
        $query = new ContentPhoto();
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
            'photoable_type' => 'required',
            'photoable_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }

        $img_count = $request->input('img_count');

        try {
            DB::beginTransaction();
            for ($i = 0; $i < $img_count; $i++) {
                $url = Storage::disk('public')->put('images', $request->file('img' . $i));
                if (empty($url)) {
                    throw new \Exception('Not Found File');
                }
                $model = new ContentPhoto();
                $model->photoable_type = $request->input('photoable_type', '');
                $model->photoable_id = $request->input('photoable_id', 0);
                $model->alt = $request->input('alt');
                $model->image = $url;
                $model->order = $request->input('order');
                $model->save();
            }
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
        $model = ContentPhoto::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        return $this->success($model);
    }

    public function update(Request $request, $id)
    {
        $model = (new ContentPhoto())->find($id);
        $url = $model->image;
        $oldFileURL = $model->image;
        if ($request->hasFile('image')) {
            $url = Storage::disk('public')->put('images', $request->file('image'));
            if (empty($url)) {
                return $this->error('Not Found File');
            }
            FileUtil::removePublicFile($oldFileURL);
        }

        try {
            DB::beginTransaction();
            $model->alt = $request->input('alt');
            $model->image = $url;
            $model->order = $request->input('order');
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
        $model = ContentPhoto::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $url = $model->url;
        FileUtil::removePublicFile($url);
        $model->delete();
        return $this->success($model);
    }
}
