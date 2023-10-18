<?php
/**
 * Created by PhpStorm.
 * InventoryProduct: BaoHoang
 * Date: 6/5/2022
 * Time: 20:49
 */

namespace App\Repository;


use App\Common\RepositoryInterface;
use App\Models\InventoryProduct;
use App\Models\Role;

interface InventoryProductRepositoryInterface extends RepositoryInterface
{
    public function attach(InventoryProduct $user, Role $role);
    public function detach(InventoryProduct $user, Role $role);
}