<?php
/**
 * Created by PhpStorm.
 * ShippingFeeTable: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\ShippingFeeTable;
use App\Repository\ShippingFeeTableRepositoryInterface;

class ShippingFeeTableRepository extends EloquentRepository implements ShippingFeeTableRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return ShippingFeeTable::class;
    }

}