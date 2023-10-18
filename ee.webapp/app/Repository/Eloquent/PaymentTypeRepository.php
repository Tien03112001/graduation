<?php
/**
 * Created by PhpStorm.
 * PaymentType: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\PaymentType;
use App\Models\Role;
use App\Repository\PaymentTypeRepositoryInterface;

class PaymentTypeRepository extends EloquentRepository implements PaymentTypeRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return PaymentType::class;
    }

    public function attach(PaymentType $user, Role $role)
    {
        $user->roles()->attach($role->id);
    }

    public function detach(PaymentType $user, Role $role)
    {
        $user->roles()->detach($role->id);
    }

}