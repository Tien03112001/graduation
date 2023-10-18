<?php
/**
 * Created by PhpStorm.
 * MetaData: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\MetaData;
use App\Repository\MetaDataRepositoryInterface;

class MetaDataRepository extends EloquentRepository implements MetaDataRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return MetaData::class;
    }

}