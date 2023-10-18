<?php

namespace App\Http\Controllers;

use App\Common\RepositoryInterface;
use App\Common\WhereClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RestNestedController extends Controller implements RestNestedApiController
{

    protected $validatorMessages = [
        'required' => ':attribute không được để trống',
        'max' => ':attribute không được vượt quá 255 ký tự',
        'numeric' => ':attribute phải là số',
        'url' => ':attribute không đúng định dạng URL',
        'mimetypes' => ':attribute phải là file ảnh',
        'boolean' => ':attribute không đúng định dạng',
        'alpha' => ':attribute chỉ gồm chữ',
        'alpha_dash' => ':attribute chỉ gồm chữ hoặc (.) hoặc (_)',
        'alpha_num' => ':attribute chỉ gồm chữ hoặc số',
        'unique' => ':attribute :input đã tồn tại',
        'in' => ':attribute phải là 1 trong các giá trị :values',
        'email' => ':attribute không đúng định dạng'
    ];

    public function success($data, $message = 'Successfully', $status = 1)
    {
        return response()->json(['data' => $data, 'message' => $message, 'status' => $status], 200);
    }

    public function errorClient($message = 'Bad request', $payload = [])
    {
        return response()->json(['message' => $message, 'payload' => $payload, 'status' => 0], 400);
    }

    public function errorNotFound($message = 'ID không tồn tại', $payload = [])
    {
        return response()->json(['message' => $message, 'payload' => $payload, 'status' => 0], 400);
    }

    public function error($message = 'System error', $payload = [])
    {
        return response()->json(['message' => $message, 'payload' => $payload, 'status' => 0], 500);
    }

    public function notSupport($message = 'Method not support', $payload = [])
    {
        return response()->json(['message' => $message, 'payload' => $payload, 'status' => 0], 405);
    }

    protected $repository;
    protected $parentRepository;

    public function __construct(RepositoryInterface $repository, RepositoryInterface $parentRepository)
    {
        $this->repository = $repository;
        $this->parentRepository = $parentRepository;
    }

    public function indexDefault($parentId, Request $request, $foreignKey = 'parent_id', $whereClauses = [], $with = [], $withCount = [])
    {
        $parent = $this->parentRepository->findById($parentId);

        if (empty($parent)) {
            return $this->errorNotFound();
        }

        $limit = $request->input('limit');
        $orderBy = $request->input('orderBy', 'id:desc');

        array_push($whereClauses, WhereClause::query($foreignKey, $parentId));

        if ($limit) {
            $data = $this->repository->paginate($limit, $whereClauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->repository->get($whereClauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }

    public function showDefault($id, $orderBy = null, $with = [], $withCount = [])
    {
        $whereClauses = [WhereClause::query('id', $id)];
        $model = $this->repository->find($whereClauses, $orderBy, $with, $withCount);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        return $this->success($model);
    }


    public function storeDefault(Request $request, array $columns = [], array $validatorRules = [], $with = [], $withCount = [])
    {
        $validator = Validator::make($request->only($columns), $validatorRules, $this->validatorMessages);
        if ($validator->fails()) {
            return $this->errorClient($validator->errors()->first());
        }
        try {
            DB::beginTransaction();
            $attributes = $request->only($columns);
            $model = $this->repository->create($attributes, $with, $withCount);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function updateDefault(Request $request, $id, array $columns = [], array $validatorRules = [], $with = [], $withCount = [])
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        $validator = Validator::make($request->only($columns), $validatorRules, $this->validatorMessages);
        if ($validator->fails()) {
            return $this->errorClient($validator->errors()->first());
        }
        try {
            DB::beginTransaction();
            $attributes = $request->only($columns);
            $model = $this->repository->update($id, $attributes, $with, $withCount);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function destroyDefault($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        try {
            DB::beginTransaction();
            $this->repository->delete($id);
            DB::commit();
            return $this->success([]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }


    /**
     * @param Request $request
     * @param $validatorRules
     * @return null|string
     */
    public function validateRequest(Request $request, $validatorRules)
    {
        $validator = Validator::make($request->all(), $validatorRules, $this->validatorMessages);
        if ($validator->fails()) {
            return $validator->errors()->first();
        }
        return null;
    }

    /**
     * @param array $array
     * @param $validatorRules
     * @return null|string
     */
    public function validateArray(array $array, $validatorRules)
    {
        $validator = Validator::make($array, $validatorRules, $this->validatorMessages);
        if ($validator->fails()) {
            return $validator->errors()->first();
        }
        return null;
    }

    public function index($parentId, Request $request)
    {
        return $this->notSupport();
    }

    public function store($parentId, Request $request)
    {
        return $this->notSupport();
    }

    public function show($parentId, $id)
    {
        return $this->notSupport();
    }

    public function update($parentId, $id, Request $request)
    {
        return $this->notSupport();
    }

    public function destroy($parentId, $id)
    {
        return $this->notSupport();
    }
}