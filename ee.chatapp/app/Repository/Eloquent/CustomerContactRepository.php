<?php
/**
 * Created by PhpStorm.
 * CustomerContact: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\CustomerContact;
use App\Repository\CustomerContactRepositoryInterface;

class CustomerContactRepository extends EloquentRepository implements CustomerContactRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return CustomerContact::class;
    }

}