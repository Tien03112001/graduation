<?php
/**
 * Created by PhpStorm.
 * StructureDataType: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Common\Exceptions\ObjectNotFoundException;
use App\Models\StructureDataType;
use App\Repository\StructureDataTypeRepositoryInterface;

class StructureDataTypeRepository extends EloquentRepository implements StructureDataTypeRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return StructureDataType::class;
    }

}