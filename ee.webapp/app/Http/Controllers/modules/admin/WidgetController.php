<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\Widget;
use App\Repository\WidgetRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WidgetController extends RestController
{

    public function __construct(WidgetRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $whereClauses = [];
        if ($request->has('search')) {
            array_push($whereClauses, WhereClause::queryLike('name', $request->search));
        }

        return $this->indexDefault($request, $whereClauses);
    }

    public function show($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        $model->append([
            'view_html'
        ]);
        return $this->success($model);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'html' => 'required',
        ], [
            'name.required' => 'Tên không được để trống',
            'html.required' => 'Html không để trống'
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }
        try {
            DB::beginTransaction();
            $model = new Widget();
            $model->name = $request->input('name');
            $model->description = $request->input('description');
            $model->html = $request->input('html');
            $model->css = $request->input('css');
            $model->js = $request->input('js');
            $model->default = false;
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'html' => 'required',
        ], [
            'name.required' => 'Tên không được để trống',
            'html.required' => 'Html không để trống'
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }
        $model = (new Widget())->find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        try {
            DB::beginTransaction();
            $model->name = $request->input('name');
            $model->description = $request->input('description');
            $model->html = $request->input('html');
            $model->css = $request->input('css');
            $model->js = $request->input('js');
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
        $model = Widget::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->delete();
        return $this->success($model);
    }
}
