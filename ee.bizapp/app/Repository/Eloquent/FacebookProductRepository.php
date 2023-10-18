<?php
/**
 * Created by PhpStorm.
 * FacebookProduct: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\FacebookProduct;
use App\Repository\FacebookProductRepositoryInterface;

class FacebookProductRepository extends EloquentRepository implements FacebookProductRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return FacebookProduct::class;
    }

}