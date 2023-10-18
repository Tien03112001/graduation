<?php
/**
 * Created by PhpStorm.
 * AgentPage: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\AgentPage;
use App\Repository\AgentPageRepositoryInterface;

class AgentPageRepository extends EloquentRepository implements AgentPageRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return AgentPage::class;
    }

}