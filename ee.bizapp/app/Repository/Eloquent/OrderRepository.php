<?php
/**
 * Created by PhpStorm.
 * Order: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Order;
use App\Repository\OrderRepositoryInterface;

class OrderRepository extends EloquentRepository implements OrderRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Order::class;
    }

}