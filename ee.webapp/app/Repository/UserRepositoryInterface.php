<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 6/5/2022
 * Time: 20:49
 */

namespace App\Repository;


use App\Common\RepositoryInterface;
use App\Models\User;
use App\Models\Role;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function attach(User $user, Role $role);
    public function detach(User $user, Role $role);
}