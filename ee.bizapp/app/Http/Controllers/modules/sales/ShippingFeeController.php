<?php

namespace App\Http\Controllers\modules\sales;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\ShippingFee;
use App\Repository\ShippingFeeRepositoryInterface;
use Illuminate\Http\Request;

class ShippingFeeController extends RestController
{
    public function __construct(ShippingFeeRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $orderBy = $request->input('orderBy', 'id:asc');

        if ($request->has('province_id') && $request->has('district_id') && $request->has('ward_id')) {
            $data = $this->repository->find([WhereClause::query('province_id',$request->province_id),WhereClause::query('district_id',$request->district_id),WhereClause::query('ward_id',$request->ward_id)]);
            return $this->success($data);
        }

        $with = [];
        $withCount = [];

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->repository->get($clauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
        return parent::destroyDefault($id);
    }

}
