<?php
/**
 * Created by PhpStorm.
 * DynamicTableRow: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\DynamicTableRow;
use App\Repository\DynamicTableRowRepositoryInterface;

class DynamicTableRowRepository extends EloquentRepository implements DynamicTableRowRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return DynamicTableRow::class;
    }

}