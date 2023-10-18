<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\WardRepositoryInterface;
use Illuminate\Http\Request;

class WardController extends RestController
{
    public function __construct(WardRepositoryInterface $repository)
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
        if ($request->has('district_id')) {
            array_push($clauses, WhereClause::query('district_id', $request->district_id));
        }

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, []);
        } else {
            $data = $this->repository->get($clauses, $orderBy, []);
        }
        return $this->success($data);
    }

}
