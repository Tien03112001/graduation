<?php
/**
 * Created by PhpStorm.
 * Role: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\UserRole;
use App\Repository\UserRoleRepositoryInterface;

class UserRoleRepository extends EloquentRepository implements UserRoleRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return UserRole::class;
    }

}