<?php

namespace App\Http\Controllers\modules\super_admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\DynamicTable;
use App\Repository\DynamicTableRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DynamicTableController extends RestController
{

    public function __construct(DynamicTableRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $whereClauses = [];
        if ($request->has('search')) {
            array_push($whereClauses, WhereClause::queryLike('name', $request->search));
        }

        $orderBy = $request->input('orderBy', 'id:asc');
        $with = ['columns', 'rows', 'cells'];
        if ($limit) {
            $data = $this->repository->paginate($limit, $whereClauses, $orderBy, $with);
        } else {
            $data = $this->repository->get($whereClauses, $orderBy, $with);
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'name' => 'nullable|max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        try {
            DB::beginTransaction();
            $table = $this->repository->create($request->only([
                'name', 'description'
            ]));
            DB::commit();
            return $this->success($table);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }

    }

    public function update(Request $request, $id)
    {
        $table = $this->repository->findById($id);

        if (empty($table) || !($table instanceof DynamicTable)) {
            return $this->errorNotFound();
        }

        $validator = $this->validateRequest($request, [
            'name' => 'nullable|max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        try {
            DB::beginTransaction();
            $attributes = [];
            $columns = [
                'name',
                'description',
            ];
            foreach ($columns as $c) {
                if ($request->has($c)) {
                    $attributes[$c] = $request->{$c};
                }
            }
            $table = $this->repository->update($id, $attributes);
            DB::commit();
            return $this->success($table->load('columns', 'rows', 'cells'));
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        return $this->destroyDefault($id);
    }

}
