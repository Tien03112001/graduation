<?php

namespace App\Http\Controllers\modules\super_admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\Menu;
use App\Repository\MenuRepositoryInterface;
use App\Rules\MenuableTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MenuController extends RestController
{
    public function __construct(MenuRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $whereClauses = [];
        if ($request->has('menu_position_id')) {
            array_push($whereClauses, WhereClause::query('menu_position_id', $request->menu_position_id));
        }
        if ($request->has('search')) {
            array_push($whereClauses, WhereClause::query('name', $request->search));
        }
        return parent::indexDefault($request, $whereClauses);
    }

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'menuable_id' => 'nullable|numeric',
            'menuable_type' => ['nullable', new MenuableTypeRule()],
            'name' => 'required|max:255',
            'url' => 'required|url|max:255',
            'parent_id' => 'required|numeric',
            'menu_position_id' => 'required|numeric',
            'icon' => 'nullable|max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'name',
            'url',
            'parent_id',
            'menuable_id',
            'menuable_type',
            'menu_position_id',
            'icon'
        ]);

        if ($request->menuable_type == 'manual') {
            $attributes['menuable_type'] = null;
            $attributes['menuable_id'] = null;
        } else {
            $exists = DB::table($request->menuable_type)->exists();
            if (!$exists) {
                return $this->errorClient('Không hỗ trợ loại này');
            }
            $element = DB::table($request->menuable_type)
                ->where('id', $request->menuable_id)
                ->first();
            if (empty($element)) {
                return $this->errorClient('Không tìm thấy đối tượng');
            }
        }

        $attributes['type_id'] = 0;

        $lastMenu = $this->repository->find([
            WhereClause::query('menu_position_id', $attributes['menu_position_id'])
        ], 'order:desc');

        if (empty($lastMenu)) {
            $attributes['order'] = 0;
        } else {
            if ($lastMenu instanceof Menu) {
                $attributes['order'] = $lastMenu->order + 1;
            }
        }


        try {
            DB::beginTransaction();
            $model = $this->repository->create($attributes, ['parent', 'menuable']);
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
        if (!($model instanceof Menu)) {
            return $this->errorNotFound();
        }

        $validator = $this->validateRequest($request, [
            'menuable_id' => 'nullable|numeric',
            'menuable_type' => ['nullable', new MenuableTypeRule()],
            'name' => 'nullable|max:255',
            'url' => 'nullable|url|max:255',
            'parent_id' => 'nullable|numeric',
            'icon' => 'nullable|max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = [];
        $columns = [
            'menuable_id',
            'menuable_type',
            'name',
            'url',
            'parent_id',
            'icon'
        ];
        foreach ($columns as $column) {
            if ($request->has($column)) {
                $attributes[$column] = $request->input($column);
            }
        }


        if ($request->has('menuable_type')) {
            if ($request->menuable_type == 'manual') {
                $attributes['menuable_type'] = null;
                $attributes['menuable_id'] = null;
            } else {
                $exists = DB::table($request->metaable_type)->exists();
                if (!$exists) {
                    return $this->errorClient('Không hỗ trợ loại này');
                }
                $element = DB::table($request->menuable_type)->where('id', $request->menuable_id)
                    ->first();
                if (empty($element)) {
                    return $this->errorClient('Không tìm thấy đối tượng');
                }
            }
        }

        try {
            DB::beginTransaction();
            $model = $this->repository->update($model, $attributes, ['parent', 'menuable']);
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

    public function up($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorClient('ID không tồn tại');
        }

        $swapModel = $this->repository->find([WhereClause::query('order', $model->order, '<')]);

        if (empty($swapModel)) {
            return $this->errorClient('Không thể tăng thứ hạng');
        }
        try {
            DB::beginTransaction();
            $order = $model->order;
            $model = $this->repository->update($id,
                ['order' => $swapModel->order]
            );
            $swapModel = $this->repository->update($swapModel->id,
                ['order' => $order]
            );
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function down($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorClient('ID không tồn tại');
        }

        $swapModel = $this->repository->find([WhereClause::query('order', $model->order, '>')]);

        if (empty($swapModel)) {
            return $this->errorClient('Không thể giảm thứ hạng');
        }
        try {
            DB::beginTransaction();
            $order = $model->order;
            $model = $this->repository->update($id,
                ['order' => $swapModel->order]
            );
            $swapModel = $this->repository->update($swapModel->id,
                ['order' => $order]
            );
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}
