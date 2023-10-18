<?php
/**
 * Created by PhpStorm.
 * Agent: BaoHoang
 * Date: 6/5/2022
 * Time: 20:49
 */

namespace App\Repository;


use App\Common\RepositoryInterface;
use App\Models\FacebookFanpage;
use App\Models\Agent;

interface AgentRepositoryInterface extends RepositoryInterface
{
    public function attach(Agent $agent, FacebookFanpage $page);
    public function detach(Agent $agent, FacebookFanpage $page);
}