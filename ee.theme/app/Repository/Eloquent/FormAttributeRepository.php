<?php
/**
 * Created by PhpStorm.
 * FormAttribute: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\FormAttribute;
use App\Repository\FormAttributeRepositoryInterface;

class FormAttributeRepository extends EloquentRepository implements FormAttributeRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return FormAttribute::class;
    }

}