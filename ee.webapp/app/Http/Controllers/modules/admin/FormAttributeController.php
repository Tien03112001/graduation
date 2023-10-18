<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\FormAttributeRepositoryInterface;
use Illuminate\Http\Request;

class FormAttributeController extends RestController
{
    public function __construct(FormAttributeRepositoryInterface $repository)
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
        if ($request->has('form_id')) {
            array_push($clauses,WhereClause::query('form_id', $request->form_id,'='));
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
            'form_id',
            'description'
        ], [
            'name' => 'required|max:255',
            'form_id' => 'required|numeric|exists:forms,id'
        ]);
    }

    public function update(Request $request, $id)
    {
        return $this->updateDefault($request, $id, [
            'name',
            'form_id',
            'description'
        ], [
            'name' => 'required|max:255',
            'form_id' => 'required|numeric|exists:forms,id'
        ]);
    }

    public function destroy($id)
    {
        return $this->destroyDefault($id);
    }
}
