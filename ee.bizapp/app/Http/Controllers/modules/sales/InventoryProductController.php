<?php

namespace App\Http\Controllers\modules\sales;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\InventoryProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InventoryProductController extends RestController
{

    public function __construct(InventoryProductRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $orderBy = $request->input('orderBy', 'id:desc');
        $with = ['variant'];
        $withCount = [];

        if ($request->has('product_id') && Str::length($request->product_id) > 0) {
            array_push($clauses, WhereClause::query('product_id', $request->product_id));
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
        // TODO: Implement store() method.
    }

    public function show($id)
    {
        // TODO: Implement show() method.
    }

    public function update(Request $request, $id)
    {
        // TODO: Implement update() method.
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }
}
