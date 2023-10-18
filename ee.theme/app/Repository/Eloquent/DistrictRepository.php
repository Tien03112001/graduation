<?php
/**
 * Created by PhpStorm.
 * District: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\District;
use App\Repository\DistrictRepositoryInterface;

class DistrictRepository extends EloquentRepository implements DistrictRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return District::class;
    }

}