<?php
/**
 * Created by PhpStorm.
 * ConversationSession: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\ConversationSession;
use App\Repository\ConversationSessionRepositoryInterface;

class ConversationSessionRepository extends EloquentRepository implements ConversationSessionRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return ConversationSession::class;
    }

}