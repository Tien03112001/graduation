<?php
/**
 * Created by PhpStorm.
 * InventoryProduct: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\InventoryProduct;
use App\Models\Role;
use App\Repository\InventoryProductRepositoryInterface;

class InventoryProductRepository extends EloquentRepository implements InventoryProductRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return InventoryProduct::class;
    }

    public function attach(InventoryProduct $user, Role $role)
    {
        $user->roles()->attach($role->id);
    }

    public function detach(InventoryProduct $user, Role $role)
    {
        $user->roles()->detach($role->id);
    }

}