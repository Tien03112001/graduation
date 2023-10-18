<?php
/**
 * Created by PhpStorm.
 * MessageAttachment: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\MessageAttachment;
use App\Repository\MessageAttachmentRepositoryInterface;

class MessageAttachmentRepository extends EloquentRepository implements MessageAttachmentRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return MessageAttachment::class;
    }

}