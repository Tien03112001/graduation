<?php
/**
 * Created by PhpStorm.
 * SaleOrderStatus: BaoHoang
 * Date: 6/5/2022
 * Time: 20:49
 */

namespace App\Repository;


use App\Common\RepositoryInterface;
use App\Models\SaleOrderStatus;
use App\Models\Role;

interface SaleOrderStatusRepositoryInterface extends RepositoryInterface
{
    public function attach(SaleOrderStatus $user, Role $role);
    public function detach(SaleOrderStatus $user, Role $role);
}