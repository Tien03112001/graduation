<?php
/**
 * Created by PhpStorm.
 * FormValue: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\FormValue;
use App\Repository\FormValueRepositoryInterface;

class FormValueRepository extends EloquentRepository implements FormValueRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return FormValue::class;
    }

}