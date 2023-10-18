<?php
/**
 * Created by PhpStorm.
 * OrderItem: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\OrderItem;
use App\Repository\OrderItemRepositoryInterface;

class OrderItemRepository extends EloquentRepository implements OrderItemRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return OrderItem::class;
    }

}