<?php
/**
 * Created by PhpStorm.
 * Province: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Province;
use App\Repository\ProvinceRepositoryInterface;

class ProvinceRepository extends EloquentRepository implements ProvinceRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Province::class;
    }

}