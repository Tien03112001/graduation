<?php
/**
 * Created by PhpStorm.
 * AgentAccount: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\AgentAccount;
use App\Repository\AgentAccountRepositoryInterface;

class AgentAccountRepository extends EloquentRepository implements AgentAccountRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return AgentAccount::class;
    }

}