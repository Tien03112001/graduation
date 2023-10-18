<?php
/**
 * Created by PhpStorm.
 * ShippingService: BaoHoang
 * Date: 6/5/2022
 * Time: 20:49
 */

namespace App\Repository;


use App\Common\RepositoryInterface;
use App\Models\ShippingService;
use App\Models\Role;

interface ShippingServiceRepositoryInterface extends RepositoryInterface
{
    public function attach(ShippingService $user, Role $role);
    public function detach(ShippingService $user, Role $role);
}