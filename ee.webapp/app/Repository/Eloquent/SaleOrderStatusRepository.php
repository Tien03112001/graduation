<?php
/**
 * Created by PhpStorm.
 * SaleOrderStatus: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\SaleOrderStatus;
use App\Models\Role;
use App\Repository\SaleOrderStatusRepositoryInterface;

class SaleOrderStatusRepository extends EloquentRepository implements SaleOrderStatusRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return SaleOrderStatus::class;
    }

    public function attach(SaleOrderStatus $user, Role $role)
    {
        $user->roles()->attach($role->id);
    }

    public function detach(SaleOrderStatus $user, Role $role)
    {
        $user->roles()->detach($role->id);
    }

}