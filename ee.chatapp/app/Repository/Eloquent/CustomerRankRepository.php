<?php
/**
 * Created by PhpStorm.
 * CustomerRank: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\CustomerRank;
use App\Repository\CustomerRankRepositoryInterface;

class CustomerRankRepository extends EloquentRepository implements CustomerRankRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return CustomerRank::class;
    }

}