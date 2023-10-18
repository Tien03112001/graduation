<?php

namespace App\Http\Controllers\modules\inventory;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends RestController
{
    public function __construct(ProductRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $orderBy = $request->input('orderBy', 'id:asc');
        $with = ['inventories.variant'];
        $withCount = [];

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
        return $this->error('Not support for this method');
    }

    public function show($id)
    {
        return $this->error('Not support for this method');
    }

    public function update(Request $request, $id)
    {
        return $this->error('Not support for this method');
    }

    public function destroy($id)
    {
        return $this->error('Not support for this method');
    }

}

