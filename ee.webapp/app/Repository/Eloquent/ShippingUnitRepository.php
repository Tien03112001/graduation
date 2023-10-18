<?php
/**
 * Created by PhpStorm.
 * ShippingUnit: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\ShippingUnit;
use App\Models\Role;
use App\Repository\ShippingUnitRepositoryInterface;

class ShippingUnitRepository extends EloquentRepository implements ShippingUnitRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return ShippingUnit::class;
    }

    public function attach(ShippingUnit $user, Role $role)
    {
        $user->roles()->attach($role->id);
    }

    public function detach(ShippingUnit $user, Role $role)
    {
        $user->roles()->detach($role->id);
    }

}