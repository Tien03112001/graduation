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
use App\Repository\AgentRepositoryInterface;
use App\Models\FacebookFanpage;

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

    public function attach(Agent $agent, FacebookFanpage $page)
    {
        $agent->pages()->attach($page->id);
    }

    public function detach(Agent $agent, FacebookFanpage $page)
    {
        $agent->pages()->detach($page->id);
    }

}