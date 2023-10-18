<?php
/**
 * Created by PhpStorm.
 * ProductVariant: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\ProductVariant;
use App\Repository\ProductVariantRepositoryInterface;

class ProductVariantRepository extends EloquentRepository implements ProductVariantRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return ProductVariant::class;
    }

}