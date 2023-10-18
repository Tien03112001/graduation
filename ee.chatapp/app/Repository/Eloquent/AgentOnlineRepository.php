<?php
/**
 * Created by PhpStorm.
 * AgentOnline: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\AgentOnline;
use App\Repository\AgentOnlineRepositoryInterface;

class AgentOnlineRepository extends EloquentRepository implements AgentOnlineRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return AgentOnline::class;
    }

}