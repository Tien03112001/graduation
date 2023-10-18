<?php
/**
 * Created by PhpStorm.
 * SaleOrderShippingStatus: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\SaleOrderShippingStatus;
use App\Models\Role;
use App\Repository\SaleOrderShippingStatusRepositoryInterface;

class SaleOrderShippingStatusRepository extends EloquentRepository implements SaleOrderShippingStatusRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return SaleOrderShippingStatus::class;
    }

    public function attach(SaleOrderShippingStatus $user, Role $role)
    {
        $user->roles()->attach($role->id);
    }

    public function detach(SaleOrderShippingStatus $user, Role $role)
    {
        $user->roles()->detach($role->id);
    }

}