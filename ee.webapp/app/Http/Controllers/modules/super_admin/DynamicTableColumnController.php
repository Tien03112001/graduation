<?php

namespace App\Http\Controllers\modules\super_admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\DynamicTable;
use App\Models\DynamicTableColumn;
use App\Repository\DynamicTableColumnRepositoryInterface;
use App\Repository\DynamicTableRepositoryInterface;
use App\Rules\DynamicTableColumnTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DynamicTableColumnController extends RestController
{
    protected $tableRepository;

    public function __construct(DynamicTableColumnRepositoryInterface $repository,
                                DynamicTableRepositoryInterface $tableRepository)
    {
        parent::__construct($repository);
        $this->tableRepository = $tableRepository;
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
        $validator = $this->validateRequest($request, [
            'table_id' => 'required|numeric',
            'name' => 'required|max:255',
            'type' => ['required', new DynamicTableColumnTypeRule()]
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $tableId = $request->table_id;

        $table = $this->tableRepository->findById($tableId);

        if (empty($table) || !($table instanceof DynamicTable)) {
            return $this->errorNotFound();
        }

        try {
            DB::beginTransaction();
            $column = $this->repository->create([
                'table_id' => $tableId,
                'name' => $request->name,
                'description' => $request->input('description'),
                'type' => $request->type
            ]);
            DB::commit();
            return $this->success($column);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $column = $this->repository->findById($id);

        if (empty($column) || !($column instanceof DynamicTableColumn)) {
            return $this->errorNotFound();
        }

        $validator = $this->validateRequest($request, [
            'name' => 'nullable|max:255',
            'type' => ['nullable', new DynamicTableColumnTypeRule()]
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
                'type'
            ];
            foreach ($columns as $c) {
                if ($request->has($c)) {
                    $attributes[$c] = $request->{$c};
                }
            }
            $column = $this->repository->update($id, $attributes);
            DB::commit();
            return $this->success($column);
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
