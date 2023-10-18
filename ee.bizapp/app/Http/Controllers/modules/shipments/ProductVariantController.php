<?php

namespace App\Http\Controllers\modules\shipments;

use App\Http\Controllers\RestController;
use App\Common\WhereClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Repository\ProductRepositoryInterface;
use App\Repository\ProductVariantRepositoryInterface;

class ProductVariantController extends RestController
{
    protected $productRepository;

    public function __construct(ProductVariantRepositoryInterface $repository, ProductRepositoryInterface $productRepository)
    {
        parent::__construct($repository);
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $orderBy = $request->input('orderBy', 'id:asc');
        $with = ['variants'];
        $withCount = [];

        if ($request->has('search') && Str::length($request->search) > 0) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        }
        if ($request->has('search') && Str::length($request->search) == 0) {
            $data = '';
            return $this->success($data);
        }

        if ($limit) {
            $data = $this->productRepository->paginate($limit, $clauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->productRepository->get($clauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        // TODO: Implement show() method.
    }

    public function show($id)
    {
        // TODO: Implement show() method.
    }

    public function update(Request $request, $id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorClient('Đối tượng không tồn tại');
        }
        $validator = Validator::make($request->all(), [
            'weight' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        if($request->weight <= 0) {
            return $this->errorClient('Khối lượng không đúng');
        }
        $attributes['weight'] = $request->input('weight');
        try {
            DB::beginTransaction();
            $this->repository->update($id, $attributes);
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
        // TODO: Implement show() method.
    }
}
