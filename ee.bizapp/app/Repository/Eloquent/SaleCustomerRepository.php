<?php
/**
 * Created by PhpStorm.
 * SaleCustomer: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\SaleCustomer;
use App\Repository\SaleCustomerRepositoryInterface;

class SaleCustomerRepository extends EloquentRepository implements SaleCustomerRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return SaleCustomer::class;
    }

}