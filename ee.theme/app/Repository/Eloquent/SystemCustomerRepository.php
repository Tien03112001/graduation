<?php
/**
 * Created by PhpStorm.
 * SystemCustomer: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\SystemCustomer;
use App\Repository\SystemCustomerRepositoryInterface;

class SystemCustomerRepository extends EloquentRepository implements SystemCustomerRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return SystemCustomer::class;
    }

}