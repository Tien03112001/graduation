<?php
/**
 * Created by PhpStorm.
 * PostTag: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\PostTag;
use App\Repository\PostTagRepositoryInterface;

class PostTagRepository extends EloquentRepository implements PostTagRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return PostTag::class;
    }

}