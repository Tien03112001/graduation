<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\JobPosting;
use App\Repository\JobPostingRepositoryInterface;
use App\Rules\CurrencyRule;
use App\Rules\JobPostingUnitTimeRule;
use App\Utils\FileStorageUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JobPostingController extends RestController
{

    public function __construct(JobPostingRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $orderBy = $request->input('orderBy', 'id:asc');

        if ($request->has('search')) {
            array_push($clauses, WhereClause::queryLike('title', $request->search));
        }

        if ($request->has('published')) {
            array_push($clauses, WhereClause::query('published', $request->published));
        }

        if ($request->has('date_posted_from')) {
            array_push($clauses, WhereClause::queryRaw("DATE(`date_posted`) >= $request->date_posted_from"));
        }

        if ($request->has('date_posted_to')) {
            array_push($clauses, WhereClause::queryRaw("DATE(`date_posted`) <= $request->date_posted_to"));
        }

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, []);
        } else {
            $data = $this->repository->get($clauses, $orderBy, []);
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'image' => 'required|mimetypes:image/*',
            'job_location' => 'required|max:255',
            'base_salary_min' => 'nullable|numeric',
            'base_salary_max' => 'nullable|numeric',
            'unit_currency' => ['nullable', new CurrencyRule()],
            'unit_time' => ['nullable', new JobPostingUnitTimeRule()],
            'date_posted' => ['required', 'date'],
            'valid_through' => ['nullable', 'date'],
            'quantity' => 'required|numeric',
            'type' => 'nullable',
            'published' => 'required|boolean'
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'title',
            'description',
            'job_location',
            'base_salary_min',
            'base_salary_max',
            'unit_currency',
            'unit_time',
            'date_posted',
            'valid_through',
            'quantity',
            'type',
            'published'
        ]);

        $createdImages = [];
        $url = FileStorageUtil::getInstance()->putFile('images', $request->file('image'));
        if (!($url)) {
            return $this->error('Not Found File');
        }
        $attributes['image'] = $url;
        array_push($createdImages, $url);

        try {
            DB::beginTransaction();
            $model = $this->repository->create($attributes);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            FileStorageUtil::getInstance()->deleteFiles($createdImages);
            return $this->error($e->getMessage());
        }

    }

    public function update(Request $request, $id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof JobPosting)) {
            return $this->errorNotFound();
        }

        $validator = $this->validateRequest($request, [
            'title' => 'nullable|max:255',
            'description' => 'nullable',
            'job_location' => 'nullable|max:255',
            'base_salary_min' => 'nullable|numeric',
            'base_salary_max' => 'nullable|numeric',
            'unit_currency' => ['nullable', new CurrencyRule()],
            'unit_time' => ['nullable', new JobPostingUnitTimeRule()],
            'date_posted' => ['nullable', 'date'],
            'valid_through' => ['nullable', 'date'],
            'quantity' => 'nullable|numeric',
            'type' => 'nullable',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $columns = [
            'title',
            'description',
            'job_location',
            'base_salary_min',
            'base_salary_max',
            'unit_currency',
            'unit_time',
            'date_posted',
            'valid_through',
            'quantity',
            'type',
        ];
        $attributes = [];
        foreach ($columns as $c) {
            if ($request->has($c)) {
                $attributes[$c] = $request->{$c};
            }
        }

        $createdImages = [];
        if ($request->hasFile('image')) {
            $validator = $this->validateRequest($request, [
                'image' => 'mimetypes:image/*',
            ]);
            if ($validator) {
                return $this->errorClient($validator);
            }
            $url = FileStorageUtil::getInstance()->putFile('images', $request->file('image'));
            if (!($url)) {
                return $this->error('Not Found File');
            }
            $attributes['image'] = $url;
            array_push($createdImages, $url);
        }

        try {
            DB::beginTransaction();
            FileStorageUtil::getInstance()->deleteFile($model->image);
            $this->repository->update($id, $attributes);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            FileStorageUtil::getInstance()->deleteFiles($createdImages);
            return $this->error($e->getMessage());
        }

    }

    public function destroy($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof JobPosting)) {
            return $this->errorNotFound();
        }

        try {
            DB::beginTransaction();
            FileStorageUtil::getInstance()->deleteFile($model->image);
            $this->repository->delete($model);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }

    }

    public function enable($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof JobPosting)) {
            return $this->errorNotFound();
        }
        try {
            DB::beginTransaction();
            $model = $this->repository->update($id,
                ['published' => true]
            );
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function disable($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof JobPosting)) {
            return $this->errorNotFound();
        }
        try {
            DB::beginTransaction();
            $model = $this->repository->update($id,
                ['published' => false]
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
