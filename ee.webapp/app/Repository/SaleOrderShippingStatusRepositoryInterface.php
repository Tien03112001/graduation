<?php
/**
 * Created by PhpStorm.
 * SaleOrderShippingStatus: BaoHoang
 * Date: 6/5/2022
 * Time: 20:49
 */

namespace App\Repository;


use App\Common\RepositoryInterface;
use App\Models\SaleOrderShippingStatus;
use App\Models\Role;

interface SaleOrderShippingStatusRepositoryInterface extends RepositoryInterface
{
    public function attach(SaleOrderShippingStatus $user, Role $role);
    public function detach(SaleOrderShippingStatus $user, Role $role);
}