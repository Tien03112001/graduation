<?php
/**
 * Created by PhpStorm.
 * DynamicTableCell: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\DynamicTableCell;
use App\Repository\DynamicTableCellRepositoryInterface;

class DynamicTableCellRepository extends EloquentRepository implements DynamicTableCellRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return DynamicTableCell::class;
    }

}