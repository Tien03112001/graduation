<?php
/**
 * Created by PhpStorm.
 * PaymentMethod: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\PaymentMethod;
use App\Models\Role;
use App\Repository\PaymentMethodRepositoryInterface;

class PaymentMethodRepository extends EloquentRepository implements PaymentMethodRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return PaymentMethod::class;
    }

    public function attach(PaymentMethod $user, Role $role)
    {
        $user->roles()->attach($role->id);
    }

    public function detach(PaymentMethod $user, Role $role)
    {
        $user->roles()->detach($role->id);
    }

}