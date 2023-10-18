<?php

namespace App\Http\Controllers\modules\third_party;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\DistrictRepositoryInterface;
use App\Repository\ProvinceRepositoryInterface;
use App\Repository\ShippingFeeTableRepositoryInterface;
use App\Repository\WardRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShippingFeeTableController extends RestController
{
    /* @var ShippingFeeTableRepositoryInterface */
    protected $repository;
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

        if ($request->province) {
            $search = $request->province;
            array_push($clauses, WhereClause::queryRelationHas('province', function (Builder $q) use ($search) {
                $q->where('name', 'like', "%$search%");
            }));
        }


        if ($request->district) {
            $search = $request->district;
            array_push($clauses, WhereClause::queryRelationHas('district', function (Builder $q) use ($search) {
                $q->where('name', 'like', "%$search%");
            }));
        }

        if ($request->ward) {
            $search = $request->ward;
            array_push($clauses, WhereClause::queryRelationHas('ward', function (Builder $q) use ($search) {
                $q->where('name', 'like', "%$search%");
            }));
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
        set_time_limit(-1);
        $validator = $this->validateRequest($request, [
            'url' => 'required|url',
            'default_shipping_fee' => 'required|numeric'
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        try {
            $text = file_get_contents(
                $request->input('url'),
                false,
                stream_context_create([
                    "ssl" => [
                        "verify_peer" => false,
                        "verify_peer_name" => false,
                    ],
                ])
            );
            if (!$text) {
                throw new \Exception('Không thể đọc nội dung từ url');
            }
            $data = json_decode($text, true);
            if (empty($data)) {
                throw new \Exception('Không thể đọc nội dung từ url');
            }
        } catch (\Exception $e) {
            return $this->errorClient($e->getMessage());
        }

        try {
            DB::beginTransaction();
            $this->provinceRepository->truncate();
            $this->districtRepository->truncate();
            $this->wardRepository->truncate();
            $this->repository->truncate();
            foreach ($data['data'] as $province) {
                $p = $this->provinceRepository->create([
                    'name' => $province['name'],
                    'code' => $province['level1_id']
                ]);
                foreach ($province['level2s'] as $district) {
                    $d = $this->districtRepository->create([
                        'name' => $district['name'],
                        'code' => $district['level2_id'],
                        'province_id' => $p->id
                    ]);
                    foreach ($district['level3s'] as $ward) {
                        $w = $this->wardRepository->create([
                            'name' => $ward['name'],
                            'code' => $ward['level3_id'],
                            'province_id' => $p->id,
                            'district_id' => $d->id,
                        ]);
                        $this->repository->create([
                            'province_id' => $p->id,
                            'district_id' => $d->id,
                            'ward_id' => $w->id,
                            'fee' => $request->default_shipping_fee,
                        ]);
                    }
                }

            }
            DB::commit();
            return $this->success([]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->errorClient($e->getMessage());
        }
    }

}
