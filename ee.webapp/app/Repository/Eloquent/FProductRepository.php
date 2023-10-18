<?php
/**
 * Created by PhpStorm.
 * FProduct: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\FProduct;
use App\Repository\FProductRepositoryInterface;

class FProductRepository extends EloquentRepository implements FProductRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return FProduct::class;
    }

}