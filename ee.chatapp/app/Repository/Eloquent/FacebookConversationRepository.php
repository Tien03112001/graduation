<?php
/**
 * Created by PhpStorm.
 * FacebookConversation: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\FacebookConversation;
use App\Repository\FacebookConversationRepositoryInterface;

class FacebookConversationRepository extends EloquentRepository implements FacebookConversationRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return FacebookConversation::class;
    }

}