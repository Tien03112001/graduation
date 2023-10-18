<?php

namespace App\Http\Controllers\web;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\District;
use App\Models\Province;
use App\Repository\DistrictRepositoryInterface;
use App\Repository\ProvinceRepositoryInterface;
use App\Repository\ShippingFeeTableRepositoryInterface;
use App\Repository\WardRepositoryInterface;
use Illuminate\Http\Request;

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

    public function getDistricts($provinceId, Request $request)
    {
        $province = $this->provinceRepository->findById($provinceId, ['districts']);
        if (empty($province) || !($province instanceof Province)) {
            return [];
        }
        $districts = [];
        foreach ($province->districts as $district) {
            array_push($districts, [
                'id' => $district->id,
                'name' => $district->name,
            ]);
        }
        return $this->success($districts);
    }

    public function getWards($districtId, Request $request)
    {
        $district = $this->districtRepository->findById($districtId, ['wards']);
        if (empty($district) || !($district instanceof District)) {
            return [];
        }
        $wards = [];
        foreach ($district->wards as $ward) {
            array_push($wards, [
                'id' => $ward->id,
                'name' => $ward->name,
            ]);
        }
        return $this->success($wards);
    }

    public function getFee(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'province_id' => 'required|numeric',
            'district_id' => 'required|numeric',
            'ward_id' => 'required|numeric',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $table = $this->repository->find([
            WhereClause::query('province_id', $request->province_id),
            WhereClause::query('district_id', $request->district_id),
            WhereClause::query('ward_id', $request->ward_id),
        ]);
        if (empty($table)) {
            return $this->errorClient('Không có biểu phí');
        } else {
            return $this->success(['fee' => $table->fee]);
        }
    }
}
