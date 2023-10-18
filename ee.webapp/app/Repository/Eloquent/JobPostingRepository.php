<?php
/**
 * Created by PhpStorm.
 * JobPosting: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\JobPosting;
use App\Repository\JobPostingRepositoryInterface;

class JobPostingRepository extends EloquentRepository implements JobPostingRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return JobPosting::class;
    }

}