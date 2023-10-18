<?php

namespace App\Http\Controllers\modules\third_party;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\StructureDataPropertyRepositoryInterface;
use App\Repository\StructureDataTypeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StructureDataTypeController extends RestController
{
    protected $propertyRepository;

    public function __construct(StructureDataTypeRepositoryInterface $repository, StructureDataPropertyRepositoryInterface $propertyRepository)
    {
        parent::__construct($repository);
        $this->propertyRepository = $propertyRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $orderBy = $request->input('orderBy', 'id:asc');

        if ($request->has('search')) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        }

        $with = ['properties'];
        $withCount = [];

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->repository->get($clauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $with = ['properties'];
        $withCount = [];
        $validator = $this->validateRequest($request, [
            'name' => 'required|max:255',
            'properties' => 'required|max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $attributes = $request->only([
            'name',
        ]);
        $properties = $request->input('properties');

        $attributes['relations'] = [
            'properties' => []
        ];

        foreach ($properties as $p) {
            array_push($attributes['relations']['properties'], [
                'name' => $p['name'],
                'value_type' => $p['value_type'],
                'value' => empty($p['value']) ? null : $p['value'],
                'possible_values' => empty($p['possible_values']) ? null : $p['possible_values'],
            ]);
        }

        try {
            DB::beginTransaction();
            $model = $this->repository->create($attributes, $with, $withCount);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        try {
            DB::beginTransaction();
            $this->repository->delete($id, ['properties']);
            DB::commit();
            return $this->success([]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

}
