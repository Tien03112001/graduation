<?php
/**
 * Created by PhpStorm.
 * FacebookFanpage: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\FacebookFanpage;
use App\Repository\FacebookFanpageRepositoryInterface;

class FacebookFanpageRepository extends EloquentRepository implements FacebookFanpageRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return FacebookFanpage::class;
    }

}