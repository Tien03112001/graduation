<?php
/**
 * Created by PhpStorm.
 * ShippingUnit: BaoHoang
 * Date: 6/5/2022
 * Time: 20:49
 */

namespace App\Repository;


use App\Common\RepositoryInterface;
use App\Models\ShippingUnit;
use App\Models\Role;

interface ShippingUnitRepositoryInterface extends RepositoryInterface
{
    public function attach(ShippingUnit $user, Role $role);
    public function detach(ShippingUnit $user, Role $role);
}