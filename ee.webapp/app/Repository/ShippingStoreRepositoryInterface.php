<?php
/**
 * Created by PhpStorm.
 * ShippingStore: BaoHoang
 * Date: 6/5/2022
 * Time: 20:49
 */

namespace App\Repository;


use App\Common\RepositoryInterface;
use App\Models\ShippingStore;
use App\Models\Role;

interface ShippingStoreRepositoryInterface extends RepositoryInterface
{
    public function attach(ShippingStore $user, Role $role);
    public function detach(ShippingStore $user, Role $role);
}