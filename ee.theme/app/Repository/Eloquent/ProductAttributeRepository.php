<?php
/**
 * Created by PhpStorm.
 * ProductAttribute: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\ProductAttribute;
use App\Repository\ProductAttributeRepositoryInterface;

class ProductAttributeRepository extends EloquentRepository implements ProductAttributeRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return ProductAttribute::class;
    }

}