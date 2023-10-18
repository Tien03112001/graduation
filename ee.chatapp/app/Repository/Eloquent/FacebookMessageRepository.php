<?php
/**
 * Created by PhpStorm.
 * FacebookMessage: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\FacebookMessage;
use App\Repository\FacebookMessageRepositoryInterface;

class FacebookMessageRepository extends EloquentRepository implements FacebookMessageRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return FacebookMessage::class;
    }

}