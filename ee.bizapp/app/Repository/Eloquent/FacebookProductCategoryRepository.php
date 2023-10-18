<?php
/**
 * Created by PhpStorm.
 * FacebookProductCategory: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\FacebookProductCategory;
use App\Repository\FacebookProductCategoryRepositoryInterface;

class FacebookProductCategoryRepository extends EloquentRepository implements FacebookProductCategoryRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return FacebookProductCategory::class;
    }

}