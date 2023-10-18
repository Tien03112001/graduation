<?php
/**
 * Created by PhpStorm.
 * FacebookWebhookPostChangedRequest: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\FacebookWebhookPostChangedRequest;
use App\Repository\FacebookWebhookPostChangedRequestRepositoryInterface;

class FacebookWebhookPostChangedRequestRepository extends EloquentRepository implements FacebookWebhookPostChangedRequestRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return FacebookWebhookPostChangedRequest::class;
    }

}