<?php
/**
 * Created by PhpStorm.
 * Message: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Message;
use App\Repository\MessageRepositoryInterface;

class MessageRepository extends EloquentRepository implements MessageRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Message::class;
    }

}