<?php
/**
 * Created by PhpStorm.
 * Widget: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Widget;
use App\Repository\WidgetRepositoryInterface;

class WidgetRepository extends EloquentRepository implements WidgetRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Widget::class;
    }

}