<?php

namespace App\Http\Controllers\modules\admin;

use App\Models\ProductType;
use App\Utils\FileUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductTypeController extends Controller
{
    public function index(Request $request){
        $limit = $request->input('limit');
        $search = $request->input('search');
        $sql = (new ProductType())->when($search, function ($q) use($search){
            return $q->where('name', $search);
        });
        if ($limit){
            $data = $sql->paginate($limit);
        }else{
            $data = $sql->get();
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $model = new ProductType();
        $model->name = $request->input('name');
        $model->order = $request->input('order');
        $model->published = $request->input('published');
        if ($request->hasFile('image')) {
            $url = Storage::disk('public')->put('images', $request->file('image'));
            if (empty($url)) {
                return $this->error('Not Found File');
            }
            $model->image = $url;
            $model->alt = $request->input('alt', $model->name);
        }
        try {
            DB::beginTransaction();
            $model->save();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $model = (new ProductType())->find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->name = $request->input('name');
        $model->published = $request->input('published');
        $model->order = $request->input('order');
        if ($request->hasFile('image')) {
            $url = Storage::disk('public')->put('images', $request->file('image'));
            if (empty($url)) {
                return $this->error('Not Found File');
            }
            $model->image = $url;
            $model->alt = $request->input('alt', $model->name);
        }

        try {
            DB::beginTransaction();
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
        $model = ProductType::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->delete();
        return $this->success($model);
    }

    public function change_order(Request $request, $id){
        $data = ProductType::find($id);
        $data->order = $request->order;
        $data->save();
        return $this->success($data);
    }

    public function enable($id)
    {
        $model = ProductType::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        try {
            DB::beginTransaction();
            $model->published = true;
            $model->save();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function disable($id)
    {
        $model = ProductType::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        try {
            DB::beginTransaction();
            $model->published = false;
            $model->save();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}
