<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\District;
use App\Models\Province;
use App\Models\ShippingFeeTable;
use App\Models\Ward;
use App\Repository\DistrictRepositoryInterface;
use App\Repository\ProvinceRepositoryInterface;
use App\Repository\ShippingFeeTableRepositoryInterface;
use App\Repository\WardRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShippingFeeTableController extends RestController
{
    protected $provinceRepository;
    protected $districtRepository;
    protected $wardRepository;

    public function __construct(ShippingFeeTableRepositoryInterface $repository,
                                ProvinceRepositoryInterface $provinceRepository,
                                DistrictRepositoryInterface $districtRepository,
                                WardRepositoryInterface $wardRepository)
    {
        parent::__construct($repository);
        $this->provinceRepository = $provinceRepository;
        $this->districtRepository = $districtRepository;
        $this->wardRepository = $wardRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $orderBy = $request->input('orderBy', 'id:asc');

        if ($request->has('search')) {
            array_push($clauses, WhereClause::orQuery([
                WhereClause::queryWhereHas('province', [WhereClause::queryLike('name', $request->search)]),
                WhereClause::queryWhereHas('district', [WhereClause::queryLike('name', $request->search)]),
                WhereClause::queryWhereHas('ward', [WhereClause::queryLike('name', $request->search)]),
            ]));
        }

        if ($request->has('province_id')) {
            array_push($clauses, WhereClause::query('province_id', $request->province_id));
        }
        if ($request->has('district_id')) {
            array_push($clauses, WhereClause::query('district_id', $request->district_id));
        }

        if ($request->has('ward_id')) {
            array_push($clauses, WhereClause::query('ward_id', $request->ward_id));
        }

        $with = ['province', 'district', 'ward'];
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
        $validator = $this->validateRequest($request, [
            'province_id' => 'nullable|numeric',
            'district_id' => 'nullable|numeric',
            'ward_id' => 'nullable|numeric',
            'fee' => 'required|numeric',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $clauses = [];

        if ($request->has('ward_id')) {
            $model = $this->wardRepository->findById($request->ward_id);
            if (empty($model)) {
                return $this->errorNotFound();
            }
            if (!($model instanceof Ward)) {
                return $this->errorNotFound();
            }
            array_push($clauses, WhereClause::query('ward_id', $request->ward_id));
        }

        if ($request->has('district_id')) {
            $model = $this->districtRepository->findById($request->district_id);
            if (empty($model)) {
                return $this->errorNotFound();
            }
            if (!($model instanceof District)) {
                return $this->errorNotFound();
            }
            array_push($clauses, WhereClause::query('district_id', $request->district_id));
        }

        if ($request->has('province_id')) {
            $model = $this->provinceRepository->findById($request->province_id);
            if (empty($model)) {
                return $this->errorNotFound();
            }
            if (!($model instanceof Province)) {
                return $this->errorNotFound();
            }
            array_push($clauses, WhereClause::query('province_id', $request->province_id));
        }


        try {
            DB::beginTransaction();
            $model = $this->repository->bulkUpdate($clauses, $request->only([
                'fee'
            ]));
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
        if (!($model instanceof ShippingFeeTable)) {
            return $this->errorNotFound();
        }
        $validator = $this->validateRequest($request, [
            'fee' => 'required|numeric',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, $request->only(['fee']));
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

}
