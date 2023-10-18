<?php
/**
 * Created by PhpStorm.
 * Channel: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Channel;
use App\Models\Role;
use App\Repository\ChannelRepositoryInterface;

class ChannelRepository extends EloquentRepository implements ChannelRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Channel::class;
    }

    public function attach(Channel $user, Role $role)
    {
        $user->roles()->attach($role->id);
    }

    public function detach(Channel $user, Role $role)
    {
        $user->roles()->detach($role->id);
    }

}