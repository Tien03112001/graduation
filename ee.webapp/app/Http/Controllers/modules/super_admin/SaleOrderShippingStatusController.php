<?php

namespace App\Http\Controllers\modules\super_admin;

use App\Http\Controllers\RestController;
use App\Common\WhereClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\SaleOrderShippingStatus;
use App\Repository\SaleOrderShippingStatusRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SaleOrderShippingStatusController extends RestController
{

    public function __construct(SaleOrderShippingStatusRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $with = [];
        $withCount = [];
        $orderBy = $request->input('orderBy', 'id:desc');
        if ($request->has('search') && Str::length($request->search) > 0) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        }
        if ($request->has('search') && Str::length($request->search) == 0) {
            $data = '';
            return $this->success($data);
        }
        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->repository->get($clauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }


    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'name' => ['required'],
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $attributes = $request->only([
            'name',
        ]);
        $attributes['value']=$attributes['name'];
        $attributes['priority']=1;
        $name_test = $this->repository->get([WhereClause::query('name', $request->input('name'))])->first();
        if ($name_test) {
            return $this->errorClient('Trạng thái ship đã tồn tại');
        }
        try {
            DB::beginTransaction();
            $model = $this->repository->create($attributes);
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
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof SaleOrderShippingStatus)) {
            return $this->errorNotFound();
        }

        $validator = $this->validateRequest($request, [
            'name' => 'required|max:255',

        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes['name']=$request->input('name');
        $attributes['value']=$attributes['name'];

        $name_test = $this->repository->get([WhereClause::query('name', $request->input('name')),WhereClause::queryDiff('id', $model->id)],[],[],[])->first();
        if ($name_test) {
            return $this->errorClient('Trạng thái đã tồn tại');
        }
        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, $attributes);
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
        $model = (new SaleOrderShippingStatus())->find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->delete();
        return $this->success([]);
    }
}
