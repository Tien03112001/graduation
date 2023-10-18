<?php
/**
 * Created by PhpStorm.
 * SalesMember: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\SalesMember;
use App\Repository\SalesMemberRepositoryInterface;

class SalesMemberRepository extends EloquentRepository implements SalesMemberRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return SalesMember::class;
    }

}