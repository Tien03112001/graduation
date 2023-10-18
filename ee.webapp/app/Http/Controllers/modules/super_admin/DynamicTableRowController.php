<?php

namespace App\Http\Controllers\modules\super_admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\DynamicTable;
use App\Models\DynamicTableRow;
use App\Repository\DynamicTableCellRepositoryInterface;
use App\Repository\DynamicTableColumnRepositoryInterface;
use App\Repository\DynamicTableRepositoryInterface;
use App\Repository\DynamicTableRowRepositoryInterface;
use App\Utils\FileStorageUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DynamicTableRowController extends RestController
{
    protected $columnRepository;
    protected $tableRepository;
    protected $cellRepository;

    public function __construct(DynamicTableRowRepositoryInterface $repository,
                                DynamicTableColumnRepositoryInterface $columnRepository,
                                DynamicTableRepositoryInterface $tableRepository,
                                DynamicTableCellRepositoryInterface $cellRepository)
    {
        parent::__construct($repository);
        $this->columnRepository = $columnRepository;
        $this->tableRepository = $tableRepository;
        $this->cellRepository = $cellRepository;
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
            array_push($whereClauses, WhereClause::queryLike('row_value', $request->search));
        }
        $orderBy = $request->input('orderBy', 'id:asc');
        $with = ['table', 'cells.column'];

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
            'table_id' => 'required|numeric'
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $tableId = $request->table_id;

        $table = $this->tableRepository->findById($tableId, ['columns']);

        if (empty($table) || !($table instanceof DynamicTable)) {
            return $this->errorNotFound();
        }

        $rules = [];
        foreach ($table->columns as $column) {
            $rule = 'required';
            if ($column->type == 'image') {
                $rule .= '|mimetypes:image/*';
            } else if ($column->type == 'file') {
                $rule .= '|file';
            } else if ($column->type == 'number') {
                $rule .= '|numeric';
            }
            $rules["column$column->id"] = $rule;
        }

        $validator = $this->validateRequest($request, $rules);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $createdImages = [];
        try {
            DB::beginTransaction();
            $row = $this->repository->create([
                'table_id' => $tableId,
                'row_value' => Str::random()
            ]);

            foreach ($table->columns as $column) {
                $cellAttributes = [
                    'table_id' => $tableId,
                    'column_id' => $column->id,
                    'row_id' => $row->id,
                    'cell_value' => $request->input("column$column->id")
                ];
                $columnName = "column$column->id";
                if ($column->type == 'image') {
                    $url = FileStorageUtil::getInstance()->putFile('images', $request->file($columnName));
                    if (!($url)) {
                        return $this->error('Not Found File');
                    }
                    $cellAttributes['cell_value'] = $url;
                    array_push($createdImages, $url);
                } else if ($column->type == 'file') {
                    $url = FileStorageUtil::getInstance()->putFile('files', $request->file($columnName));
                    if (!($url)) {
                        return $this->error('Not Found File');
                    }
                    $cellAttributes['cell_value'] = $url;
                    array_push($createdImages, $url);
                }
                $this->cellRepository->create($cellAttributes);
            }

            DB::commit();
            return $this->success($row->load('cells.column'));
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $row = $this->repository->findById($id, ['cells']);

        if (empty($row) || !($row instanceof DynamicTableRow)) {
            return $this->errorNotFound();
        }
        try {
            DB::beginTransaction();
            foreach ($row->cells as $cell) {
                $this->cellRepository->delete($cell);
            }
            $this->repository->delete($row);
            DB::commit();
            return $this->success($row);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}
