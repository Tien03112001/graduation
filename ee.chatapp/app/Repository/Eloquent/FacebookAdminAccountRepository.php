<?php
/**
 * Created by PhpStorm.
 * FacebookAdminAccount: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\FacebookAdminAccount;
use App\Repository\FacebookAdminAccountRepositoryInterface;

class FacebookAdminAccountRepository extends EloquentRepository implements FacebookAdminAccountRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return FacebookAdminAccount::class;
    }

}