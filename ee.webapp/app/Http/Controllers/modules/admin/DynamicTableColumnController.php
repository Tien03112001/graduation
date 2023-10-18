<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\DynamicTableColumnRepositoryInterface;
use Illuminate\Http\Request;

class DynamicTableColumnController extends RestController
{

    public function __construct(DynamicTableColumnRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'table_id' => 'required|numeric'
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $limit = $request->input('limit', null);
        $whereClauses = [
            WhereClause::query('table_id', $request->table_id)
        ];
        if ($request->has('search')) {
            array_push($whereClauses, WhereClause::queryLike('name', $request->search));
        }
        $orderBy = $request->input('orderBy', 'id:asc');
        $with = ['table'];

        if ($limit) {
            $data = $this->repository->paginate($limit, $whereClauses, $orderBy, $with);
        } else {
            $data = $this->repository->get($whereClauses, $orderBy, $with);
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        return $this->storeDefault($request, [
            'name',
            'table_id',
            'type',
            'description'
        ], [
            'name' => 'required|max:255',
            'type' => 'required',
            'table_id' => 'required|numeric'
        ]);
    }

    public function update(Request $request, $id)
    {
        return $this->updateDefault($request, $id, [
            'name',
            'type',
            'description'
        ], [
            'name' => 'required|max:255',
            'type' => 'required',
        ]);
    }

    public function destroy($id)
    {
        return $this->destroyDefault($id);
    }

}
