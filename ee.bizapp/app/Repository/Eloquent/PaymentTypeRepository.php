<?php
/**
 * Created by PhpStorm.
 * PaymentType: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\PaymentType;
use App\Repository\PaymentTypeRepositoryInterface;

class PaymentTypeRepository extends EloquentRepository implements PaymentTypeRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return PaymentType::class;
    }

}