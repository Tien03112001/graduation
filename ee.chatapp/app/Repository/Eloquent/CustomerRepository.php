<?php
/**
 * Created by PhpStorm.
 * Customer: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Customer;
use App\Repository\CustomerRepositoryInterface;

class CustomerRepository extends EloquentRepository implements CustomerRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Customer::class;
    }

}