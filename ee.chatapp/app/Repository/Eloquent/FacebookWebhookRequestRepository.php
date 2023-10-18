<?php
/**
 * Created by PhpStorm.
 * FacebookWebhookRequest: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\FacebookWebhookRequest;
use App\Repository\FacebookWebhookRequestRepositoryInterface;

class FacebookWebhookRequestRepository extends EloquentRepository implements FacebookWebhookRequestRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return FacebookWebhookRequest::class;
    }

}