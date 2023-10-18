<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RestController extends Controller
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

}