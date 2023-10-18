<?php
/**
 * Created by PhpStorm.
 * DynamicTable: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\DynamicTable;
use App\Repository\DynamicTableRepositoryInterface;

class DynamicTableRepository extends EloquentRepository implements DynamicTableRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return DynamicTable::class;
    }

}