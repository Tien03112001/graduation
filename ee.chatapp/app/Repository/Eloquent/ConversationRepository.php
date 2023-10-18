<?php
/**
 * Created by PhpStorm.
 * Conversation: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Conversation;
use App\Repository\ConversationRepositoryInterface;

class ConversationRepository extends EloquentRepository implements ConversationRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Conversation::class;
    }

}