<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\DynamicTableRepositoryInterface;
use App\Repository\DynamicTableColumnRepositoryInterface;
use App\Repository\DynamicTableRowRepositoryInterface;
use App\Repository\DynamicTableCellRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\DynamicTable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DynamicTableController extends RestController
{

    protected $columnRepository;

    public function __construct(
        DynamicTableRepositoryInterface $repository,
        DynamicTableColumnRepositoryInterface $columnRepository,
        DynamicTableRowRepositoryInterface $rowRepository,
        DynamicTableCellRepositoryInterface $cellRepository
    ) {
        parent::__construct($repository);
        $this->columnRepository = $columnRepository;
        $this->rowRepository = $rowRepository;
        $this->cellRepository = $cellRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $whereClauses = [];
        if ($request->has('search')) {
            array_push($whereClauses, WhereClause::queryLike('name', $request->search));
        }

        $orderBy = $request->input('orderBy', 'id:asc');
        $with = ['columns', 'rows.cells'];
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
        $model = $this->repository->findById($id, ['columns', 'rows', 'cells']);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof DynamicTable)) {
            return $this->errorNotFound();
        }
        try {
            DB::beginTransaction();
            foreach ($model->columns as $column) {
                $this->columnRepository->delete($column);
            }
            foreach ($model->rows as $row) {
                $this->rowRepository->delete($row);
            }
            foreach ($model->cells as $cell) {
                $this->cellRepository->delete($cell);
            }
            $this->repository->delete($model);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}
