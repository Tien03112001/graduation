<?php
/**
 * Created by PhpStorm.
 * Product: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Product;
use App\Repository\ProductRepositoryInterface;

class ProductRepository extends EloquentRepository implements ProductRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Product::class;
    }

}