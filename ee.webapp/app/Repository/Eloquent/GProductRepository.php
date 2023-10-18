<?php
/**
 * Created by PhpStorm.
 * GProduct: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\GProduct;
use App\Repository\GProductRepositoryInterface;

class GProductRepository extends EloquentRepository implements GProductRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return GProduct::class;
    }

}