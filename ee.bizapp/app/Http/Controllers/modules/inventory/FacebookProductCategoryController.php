<?php

namespace App\Http\Controllers\modules\inventory;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\FacebookProductCategoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Utils\OfficeUtil;

class FacebookProductCategoryController extends RestController
{
    public function __construct(FacebookProductCategoryRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $with = [];
        $withCount = [];
        $orderBy = $request->input('orderBy', 'id:asc');

        if ($request->has('search') && Str::length($request->search) > 0) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        }
        if ($request->has('search') && Str::length($request->search) == 0) {
            $data = '';
            return $this->success($data);
        }

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->repository->get($clauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        // TODO: Implement store() method.
    }

    public function update(Request $request, $id)
    {
        // TODO: Implement update() method.
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }

    public function import(Request $request)
    {
        if (!$request->hasFile('file')) {
            return $this->errorClient('Không có file');
        }

        $file = $request->file('file');
        if ($file->getClientOriginalExtension() != 'xlsx') {
            return $this->errorClient('Không đúng định dạng file .xlsx');
        }

        $newData = OfficeUtil::readXLSX($file->getRealPath(), 0, 2, 'A', -1, 'D');

        if (!empty($newData)) {
            foreach ($newData as $key => $row) {
                $i = $key + 1;
                $idValue = $row[0];
                $nameValue = $row[1];
                if (empty($idValue) || empty($nameValue)) {
                    return $this->errorClient('Lỗi dữ liệu dòng ' . $i);
                }
            }
            try {
                DB::beginTransaction();
                foreach ($newData as $row) {
                    $idValue = $row[0];
                    $nameValue = $row[1];
                    $additionalValue = trim($row[2]);

                    $attributes['id'] = $idValue;
                    $attributes['name'] = $nameValue;
                    $attributes['additional_field'] = $additionalValue;
                    $this->repository->create($attributes);
                    DB::commit();
                }
            } catch (\Exception $e) {
                Log::error($e);
                DB::rollBack();
                return $this->error($e->getMessage());
            }
            sleep(0.5);
        }
    }

}
