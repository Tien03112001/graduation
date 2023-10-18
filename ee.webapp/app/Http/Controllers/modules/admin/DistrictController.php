<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\DistrictRepositoryInterface;
use Illuminate\Http\Request;

class DistrictController extends RestController
{
    public function __construct(DistrictRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $orderBy = $request->input('orderBy', 'id:asc');

        if ($request->has('search')) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        }

        if ($request->has('province_id')) {
            array_push($clauses, WhereClause::query('province_id', $request->province_id));
        }

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, []);
        } else {
            $data = $this->repository->get($clauses, $orderBy, []);
        }
        return $this->success($data);
    }


}
