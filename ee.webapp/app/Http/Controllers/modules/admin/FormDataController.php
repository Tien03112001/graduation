<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\FormDataRepositoryInterface;
use Illuminate\Http\Request;

class FormDataController extends RestController
{

    public function __construct(FormDataRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'form_id' => 'required|numeric'
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $limit = $request->input('limit', null);
        $clauses = [];
        $orderBy = $request->input('orderBy', 'id:desc');
        array_push($clauses, WhereClause::queryLike('form_id', $request->form_id));

        $with = ['form', 'form_values.form_attribute'];

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, $with);
        } else {
            $data = $this->repository->get($clauses, $orderBy, $with);
        }
        return $this->success($data);
    }

}
