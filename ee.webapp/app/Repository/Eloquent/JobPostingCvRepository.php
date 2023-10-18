<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\JobPostingCv;
use App\Repository\JobPostingCvRepositoryInterface;

class JobPostingCvRepository extends EloquentRepository implements JobPostingCvRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return JobPostingCv::class;
    }

}