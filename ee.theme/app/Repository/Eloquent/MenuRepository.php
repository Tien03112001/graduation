<?php
/**
 * Created by PhpStorm.
 * Menu: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Menu;
use App\Repository\MenuRepositoryInterface;

class MenuRepository extends EloquentRepository implements MenuRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Menu::class;
    }

}