<?php
/**
 * Created by PhpStorm.
 * Agent: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Agent;
use App\Models\FacebookFanpage;
use App\Repository\AgentRepositoryInterface;

class AgentRepository extends EloquentRepository implements AgentRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Agent::class;
    }

    public function attach(Agent $agent, FacebookFanpage $fanpage)
    {
        $agent->fanpages()->attach($fanpage->id);
    }

    public function detach(Agent $agent, FacebookFanpage $fanpage)
    {
        $agent->fanpages()->detach($fanpage->id);
    }

}