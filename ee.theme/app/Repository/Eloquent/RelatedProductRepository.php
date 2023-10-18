<?php
/**
 * Created by PhpStorm.
 * RelatedProduct: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\RelatedProduct;
use App\Repository\RelatedProductRepositoryInterface;

class RelatedProductRepository extends EloquentRepository implements RelatedProductRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return RelatedProduct::class;
    }

}