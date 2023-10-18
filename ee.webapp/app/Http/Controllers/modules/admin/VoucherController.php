<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\Voucher;
use App\Repository\VoucherRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class VoucherController extends RestController
{
    public function __construct(VoucherRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'expired_date_from' => 'nullable|date',
            'expired_date_to' => 'nullable|date',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $limit = $request->input('limit', null);
        $clauses = [];
        $orderBy = $request->input('orderBy', 'id:asc');

        if ($request->has('search')) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        }


        if ($request->has('enable')) {
            array_push($clauses, WhereClause::query('enable', $request->enable));
        }

        if ($request->has('expired_date_from')) {
            array_push($clauses, WhereClause::query('expired_date', $request->expired_date_from, '>='));
        }

        if ($request->has('expired_date_to')) {
            array_push($clauses, WhereClause::query('expired_date', $request->expired_date_to, '<='));
        }

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, []);
        } else {
            $data = $this->repository->get($clauses, $orderBy, []);
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'name' => 'required|max:255',
            'code' => 'required|max:255|unique:vouchers,code',
            'quantity' => 'required|numeric',
            'min_order_value' => 'required|numeric',
            'min_products_count' => 'required|numeric',
            'discount_value' => 'required|numeric',
            'discount_percent' => 'required|numeric',
            'free_ship' => 'required|boolean',
            'enable' => 'required',
            'expired_date' => 'required|date'
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $attributes = $request->only([
            'name',
            'code',
            'quantity',
            'min_order_value',
            'min_products_count',
            'discount_value',
            'discount_percent',
            'enable',
            'free_ship',
            'expired_date',
        ]);
        $attributes['remain_quantity'] = 0;
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
        if (!($model instanceof Voucher)) {
            return $this->errorNotFound();
        }

        $validator = $this->validateRequest($request, [
            'name' => 'nullable|max:255',
            'code' => ['nullable', 'max:255', Rule::unique('vouchers', 'code')->ignore($id)],
            'quantity' => 'nullable|numeric',
            'min_order_value' => 'nullable|numeric',
            'min_products_count' => 'nullable|numeric',
            'discount_value' => 'nullable|numeric',
            'discount_percent' => 'nullable|numeric',
            'free_ship' => 'nullable|boolean',
            'expired_date' => 'nullable|date'
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $columns = [
            'name',
            'code',
            'quantity',
            'min_order_value',
            'min_products_count',
            'discount_value',
            'discount_percent',
            'free_ship',
            'expired_date',
        ];


        $attributes = [];
        foreach ($columns as $column) {
            if ($request->has($column)) {
                $attributes[$column] = $request->{$column};
            }
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
        return $this->destroyDefault($id);
    }

    public function enable($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        try {
            DB::beginTransaction();
            $model = $this->repository->update($id,
                ['enable' => true]
            );
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
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        try {
            DB::beginTransaction();
            $model = $this->repository->update($id,
                ['enable' => false]
            );
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}
