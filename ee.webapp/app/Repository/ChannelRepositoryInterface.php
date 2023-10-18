<?php
/**
 * Created by PhpStorm.
 * Channel: BaoHoang
 * Date: 6/5/2022
 * Time: 20:49
 */

namespace App\Repository;


use App\Common\RepositoryInterface;
use App\Models\Channel;
use App\Models\Role;

interface ChannelRepositoryInterface extends RepositoryInterface
{
    public function attach(Channel $user, Role $role);
    public function detach(Channel $user, Role $role);
}