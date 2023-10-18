<?php
/**
 * Created by PhpStorm.
 * MenuPosition: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\MenuPosition;
use App\Repository\MenuPositionRepositoryInterface;

class MenuPositionRepository extends EloquentRepository implements MenuPositionRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return MenuPosition::class;
    }

}