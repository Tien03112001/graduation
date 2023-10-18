<?php
/**
 * Created by PhpStorm.
 * StructureDataProperty: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\StructureDataProperty;
use App\Repository\StructureDataPropertyRepositoryInterface;

class StructureDataPropertyRepository extends EloquentRepository implements StructureDataPropertyRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return StructureDataProperty::class;
    }

}