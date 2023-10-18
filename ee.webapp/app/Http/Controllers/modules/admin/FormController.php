<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\FormRepositoryInterface;
use Illuminate\Http\Request;

class FormController extends RestController
{

    public function __construct(FormRepositoryInterface $repository)
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

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, []);
        } else {
            $data = $this->repository->get($clauses, $orderBy, []);
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {

        return $this->storeDefault($request, [
            'name',
            'description'
        ], [
            'name' => 'required|max:255'
        ]);
    }

    public function update(Request $request, $id)
    {
        return $this->updateDefault($request, $id, [
            'name',
            'description'
        ], [
            'name' => 'required|max:255'
        ]);
    }


    public function destroy($id)
    {
        return $this->destroyDefault($id);
    }

}
