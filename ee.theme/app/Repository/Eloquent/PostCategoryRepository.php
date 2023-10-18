<?php
/**
 * Created by PhpStorm.
 * PostCategory: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\PostCategory;
use App\Repository\PostCategoryRepositoryInterface;

class PostCategoryRepository extends EloquentRepository implements PostCategoryRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return PostCategory::class;
    }

}