<?php
/**
 * Created by PhpStorm.
 * Voucher: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Voucher;
use App\Repository\VoucherRepositoryInterface;

class VoucherRepository extends EloquentRepository implements VoucherRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Voucher::class;
    }

}