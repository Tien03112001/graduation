<?php
/**
 * Created by PhpStorm.
 * PaymentMethod: BaoHoang
 * Date: 6/5/2022
 * Time: 20:49
 */

namespace App\Repository;


use App\Common\RepositoryInterface;
use App\Models\PaymentMethod;
use App\Models\Role;

interface PaymentMethodRepositoryInterface extends RepositoryInterface
{
    public function attach(PaymentMethod $user, Role $role);
    public function detach(PaymentMethod $user, Role $role);
}