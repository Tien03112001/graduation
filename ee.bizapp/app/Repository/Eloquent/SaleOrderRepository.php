<?php
/**
 * Created by PhpStorm.
 * SaleOrder: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\SaleOrder;
use App\Repository\SaleOrderRepositoryInterface;

class SaleOrderRepository extends EloquentRepository implements SaleOrderRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return SaleOrder::class;
    }

}