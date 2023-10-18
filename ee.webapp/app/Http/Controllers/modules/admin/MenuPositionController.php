<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\MenuPosition;
use App\Repository\MenuPositionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MenuPositionController extends RestController
{

    public function __construct(MenuPositionRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $whereClauses = [];
        if ($request->has('search')) {
            array_push($whereClauses, WhereClause::query('name', $request->search));
        }
        $with = ['menus.menuable', 'menus.parent'];
        return parent::indexDefault($request, $whereClauses, $with, [], 'id:asc');
    }

    public function store(Request $request)
    {

        $validator = $this->validateRequest($request, [
            'name' => 'required|max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'name', 'description'
        ]);
        $attributes['code'] = Str::slug($request->name);

        try {
            DB::beginTransaction();
            $model = $this->repository->create($attributes);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof MenuPosition)) {
            return $this->errorNotFound();
        }
        $validator = $this->validateRequest($request, [
            'name' => 'nullable|max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $columns = [
            'name', 'description'
        ];
        $attributes = [];
        foreach ($columns as $column) {
            $attributes[$column] = $request->{$column};
        }

        if (isset($attributes['name'])) {
            $attributes['code'] = Str::slug($request->name);
        }

        try {
            DB::beginTransaction();
            $model = $this->repository->update($model, $attributes);
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
        return $this->destroyDefault($id);

    }
}
