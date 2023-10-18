<?php
/**
 * Created by PhpStorm.
 * ShippingFee: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\ShippingFee;
use App\Repository\ShippingFeeRepositoryInterface;

class ShippingFeeRepository extends EloquentRepository implements ShippingFeeRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return ShippingFee::class;
    }

}