<?php
/**
 * Created by PhpStorm.
 * StructureData: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\StructureData;
use App\Repository\StructureDataRepositoryInterface;

class StructureDataRepository extends EloquentRepository implements StructureDataRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return StructureData::class;
    }

}