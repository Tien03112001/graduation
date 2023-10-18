<?php
/**
 * Created by PhpStorm.
 * FacebookWebhookCommentChangedRequest: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\FacebookWebhookCommentChangedRequest;
use App\Repository\FacebookWebhookCommentChangedRequestRepositoryInterface;

class FacebookWebhookCommentChangedRequestRepository extends EloquentRepository implements FacebookWebhookCommentChangedRequestRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return FacebookWebhookCommentChangedRequest::class;
    }

}