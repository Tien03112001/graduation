<?php
/**
 * Created by PhpStorm.
 * ProductCategory: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\ProductCategory;
use App\Repository\ProductCategoryRepositoryInterface;

class ProductCategoryRepository extends EloquentRepository implements ProductCategoryRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return ProductCategory::class;
    }

}