<?php
/**
 * Created by PhpStorm.
 * DynamicTableColumn: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\DynamicTableColumn;
use App\Repository\DynamicTableColumnRepositoryInterface;

class DynamicTableColumnRepository extends EloquentRepository implements DynamicTableColumnRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return DynamicTableColumn::class;
    }

}