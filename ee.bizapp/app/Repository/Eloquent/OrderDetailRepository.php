<?php
/**
 * Created by PhpStorm.
 * OrderDetail: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\OrderDetail;
use App\Repository\OrderDetailRepositoryInterface;

class OrderDetailRepository extends EloquentRepository implements OrderDetailRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return OrderDetail::class;
    }

}