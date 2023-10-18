<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\ContentCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContentCompanyController extends Controller implements ApiController
{
    public function index(Request $request)
    {
        $search = $request->input('search', null);
        $limit = $request->input('limit', null);
        $query = (new ContentCompany())
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
            $model = new ContentCompany();
            $model->name = $request->input('name');
            $model->value = $request->input('value');
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
        $model = ContentCompany::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        return $this->success($model);
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $model = (new ContentCompany())->find($id);
            $model->name = $request->input('name');
            $model->value = $request->input('value');
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
        $model = ContentCompany::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->delete();
        return $this->success($model);
    }
}
