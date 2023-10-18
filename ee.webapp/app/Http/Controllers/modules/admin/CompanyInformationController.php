<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyInformation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyInformationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', null);
        $limit = $request->input('limit', null);
        $query = (new CompanyInformation())
            ->when($search, function (Builder $q) use ($search) {
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:company_information',
            'value' => 'required',
        ], [
            'name.required' => 'Trường tên bắt buộc',
            'name.unique' => 'Trường tên đã tồn tại',
            'value.required' => 'Trường giá tri bắt buộc',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        try {
            $model = new CompanyInformation();
            $model->name = $request->input('name');
            $model->value = $request->input('value');
            $model->save();
            return $this->success($model);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $model = (new CompanyInformation())->find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        try {
            $model->value = $request->input('value');
            $model->save();
            return $this->success($model);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = (new CompanyInformation())->find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->delete();
        return $this->success($model);
    }
}
