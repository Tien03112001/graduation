<?php
/**
 * Created by PhpStorm.
 * SaleOrderDetail: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\SaleOrderDetail;
use App\Repository\SaleOrderDetailRepositoryInterface;

class SaleOrderDetailRepository extends EloquentRepository implements SaleOrderDetailRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return SaleOrderDetail::class;
    }

}