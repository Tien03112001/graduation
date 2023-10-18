<?php
/**
 * Created by PhpStorm.
 * PaymentTransaction: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\PaymentTransaction;
use App\Repository\PaymentTransactionRepositoryInterface;

class PaymentTransactionRepository extends EloquentRepository implements PaymentTransactionRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return PaymentTransaction::class;
    }

}