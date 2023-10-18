<?php

namespace App\Http\Controllers;

use App\Common\RepositoryInterface;
use App\Common\WhereClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RestController extends Controller implements RestApiController
{
    protected $repository;

    protected $validatorMessages = [
        'required' => ':attribute không được để trống',
        'max' => ':attribute không được vượt quá 255 ký tự',
        'numeric' => ':attribute phải là số',
        'url' => ':attribute không đúng định dạng URL',
        'mimes' => ':attribute phải là file ảnh',
        'boolean' => ':attribute không đúng định dạng',
        'alpha' => ':attribute chỉ gồm chữ',
        'alpha_dash' => ':attribute chỉ gồm chữ hoặc (.) hoặc (_)',
        'alpha_num' => ':attribute chỉ gồm chữ hoặc số',
        'unique' => ':attribute :input đã tồn tại',
        'in' => ':attribute phải là 1 trong các giá trị :values',
        'email' => ':attribute không đúng định dạng',
        'exists' => ':attribute không tồn tại',
        'mimetypes' => ':attribute không đúng định dạng',
        'integer' => ':attribute chỉ gồm số',
    ];

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

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

    public function index(Request $request)
    {
        return $this->notSupport();
    }

    public function show($id)
    {
        return $this->notSupport();
    }

    public function store(Request $request)
    {
        return $this->notSupport();
    }

    public function update(Request $request, $id)
    {
        return $this->notSupport();
    }

    public function destroy($id)
    {
        return $this->notSupport();
    }

    public function indexDefault(Request $request, $whereClauses = [], $with = [], $withCount = [], $orderBy = null)
    {
        $limit = $request->input('limit');
        if (empty($orderBy)) {
            $orderBy = $request->input('orderBy', 'id:desc');
        }

        if ($limit) {
            $data = $this->repository->paginate($limit, $whereClauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->repository->get($whereClauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }

    public function showDefault($id, $with = [], $withCount = [])
    {
        $whereClauses = [WhereClause::query('id', $id)];
        $model = $this->repository->find($whereClauses, null, $with, $withCount);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        return $this->success($model);
    }


    public function storeDefault(Request $request, array $columns = [], array $validatorRules = [], $with = [], $withCount = [], $defaultValues = [])
    {
        $validator = Validator::make($request->only($columns), $validatorRules, $this->validatorMessages);
        if ($validator->fails()) {
            return $this->errorClient($validator->errors()->first());
        }
        try {
            DB::beginTransaction();
            $attributes = array_merge($request->only($columns), $defaultValues);
            $model = $this->repository->create($attributes, $with, $withCount);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function updateDefault(Request $request, $id, array $columns = [], array $validatorRules = [], $with = [], $withCount = [], $defaultValues = [])
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        $validator = $this->validateRequest($request, $validatorRules);
        if ($validator) {
            return $this->errorClient($validator);
        }
        try {
            DB::beginTransaction();
            $attributes = [];
            foreach ($columns as $column) {
                if ($request->has($column)) {
                    $attributes[$column] = $request->input($column);
                }
            }
            $attributes = array_merge($attributes, $defaultValues);
            $model = $this->repository->update($model, $attributes, $with, $withCount);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function destroyDefault($id, $with = [])
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        try {
            DB::beginTransaction();
            $this->repository->delete($model, $with);
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
            $errors = array_merge(...array_values($validator->errors()->getMessages()));
            return implode(', ', $errors);
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
            $errors = array_merge(...array_values($validator->errors()->getMessages()));
            return implode(', ', $errors);
        }
        return null;
    }

    protected function createClauses($columns, $method, Request $request)
    {
        $clauses = [];
        foreach ($columns as $c) {
            if ($request->has($c)) {
                array_push($clauses, WhereClause::{$method}($c, $request->{$c}));
            }
        }
        return $clauses;
    }

}