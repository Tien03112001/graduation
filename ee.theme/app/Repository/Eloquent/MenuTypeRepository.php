<?php
/**
 * Created by PhpStorm.
 * MenuType: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\MenuType;
use App\Repository\MenuTypeRepositoryInterface;

class MenuTypeRepository extends EloquentRepository implements MenuTypeRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return MenuType::class;
    }

}