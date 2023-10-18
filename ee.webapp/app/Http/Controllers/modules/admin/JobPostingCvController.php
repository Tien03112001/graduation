<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\JobPostingCv;
use App\Repository\JobPostingCvRepositoryInterface;
use App\Repository\JobPostingRepositoryInterface;
use App\Utils\FileStorageUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JobPostingCvController extends RestController
{
    protected $jobRepository;

    public function __construct(
        JobPostingCvRepositoryInterface $repository,
        JobPostingRepositoryInterface $jobRepository
    ) {
        parent::__construct($repository);
        $this->jobRepository = $jobRepository;
    }

    public function index(Request $request)
    {
        $whereClauses = [];
        if ($request->has('job_posting_id')) {
            array_push($whereClauses, WhereClause::query('job_posting_id', $request->job_posting_id));
        }
        $orderBy = $request->input('orderBy', 'id:desc');
        return $this->indexDefault($request, $whereClauses, [], [], $orderBy);
    }

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'job_posting_id' => ['required', 'numeric'],
            'name' => ['required'],
            'phone' => ['required', 'numeric'],
            'email' => ['required', 'email'],
            'cv_file' => ['required','mimes:docx,pdf']
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $job = $this->jobRepository->findById($request->job_posting_id);
        if (empty($job)) {
            return $this->errorClient('Tin tuyển dụng không tồn tại');
        }

        $attributes = $request->only([
            'job_posting_id',
            'name',
            'phone',
            'email',
            'description',
        ]);

        $createdFile = [];
        $url = FileStorageUtil::getInstance()->putFile('cv_file', $request->file('cv_file'));
        if (!($url)) {
            return $this->error('Not Found File');
        }
        $attributes['cv_file'] = $url;
        array_push($createdFile, $url);

        try {
            DB::beginTransaction();
            $model = $this->repository->create($attributes);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            FileStorageUtil::getInstance()->deleteFiles($createdFile);
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof JobPostingCv)) {
            return $this->errorNotFound();
        }
        try {
            DB::beginTransaction();
            FileStorageUtil::getInstance()->deleteFile($model->cv_file);
            $this->repository->delete($model);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }

    }
}
