<?php
/**
 * Created by PhpStorm.
 * FormData: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\FormData;
use App\Repository\FormDataRepositoryInterface;

class FormDataRepository extends EloquentRepository implements FormDataRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return FormData::class;
    }

}