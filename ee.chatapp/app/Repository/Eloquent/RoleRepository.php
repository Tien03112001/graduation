<?php
/**
 * Created by PhpStorm.
 * Role: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Role;
use App\Repository\RoleRepositoryInterface;

class RoleRepository extends EloquentRepository implements RoleRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Role::class;
    }

}