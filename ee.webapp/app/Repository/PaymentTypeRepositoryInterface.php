<?php
/**
 * Created by PhpStorm.
 * PaymentType: BaoHoang
 * Date: 6/5/2022
 * Time: 20:49
 */

namespace App\Repository;


use App\Common\RepositoryInterface;
use App\Models\PaymentType;
use App\Models\Role;

interface PaymentTypeRepositoryInterface extends RepositoryInterface
{
    public function attach(PaymentType $user, Role $role);
    public function detach(PaymentType $user, Role $role);
}