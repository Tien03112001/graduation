<?php
/**
 * Created by PhpStorm.
 * ShippingStore: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\ShippingStore;
use App\Models\Role;
use App\Repository\ShippingStoreRepositoryInterface;

class ShippingStoreRepository extends EloquentRepository implements ShippingStoreRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return ShippingStore::class;
    }

    public function attach(ShippingStore $user, Role $role)
    {
        $user->roles()->attach($role->id);
    }

    public function detach(ShippingStore $user, Role $role)
    {
        $user->roles()->detach($role->id);
    }

}