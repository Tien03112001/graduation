<?php
/**
 * Created by PhpStorm.
 * ShippingService: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\ShippingService;
use App\Models\Role;
use App\Repository\ShippingServiceRepositoryInterface;

class ShippingServiceRepository extends EloquentRepository implements ShippingServiceRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return ShippingService::class;
    }

    public function attach(ShippingService $user, Role $role)
    {
        $user->roles()->attach($role->id);
    }

    public function detach(ShippingService $user, Role $role)
    {
        $user->roles()->detach($role->id);
    }

}