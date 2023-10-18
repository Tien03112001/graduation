<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\User;
use App\Models\Role;
use App\Repository\UserRepositoryInterface;

class UserRepository extends EloquentRepository implements UserRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return User::class;
    }

    public function attach(User $user, Role $role)
    {
        $user->roles()->attach($role->id);
    }

    public function detach(User $user, Role $role)
    {
        $user->roles()->detach($role->id);
    }

}