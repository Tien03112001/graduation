<?php
/**
 * Created by PhpStorm.
 * CustomerProfile: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\CustomerProfile;
use App\Repository\CustomerProfileRepositoryInterface;

class CustomerProfileRepository extends EloquentRepository implements CustomerProfileRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return CustomerProfile::class;
    }

}