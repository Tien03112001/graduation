<?php
/**
 * Created by PhpStorm.
 * Form: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Form;
use App\Repository\FormRepositoryInterface;

class FormRepository extends EloquentRepository implements FormRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Form::class;
    }

}