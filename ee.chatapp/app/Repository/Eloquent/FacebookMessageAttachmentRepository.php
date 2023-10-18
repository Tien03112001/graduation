<?php
/**
 * Created by PhpStorm.
 * FacebookMessageAttachment: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\FacebookMessageAttachment;
use App\Repository\FacebookMessageAttachmentRepositoryInterface;

class FacebookMessageAttachmentRepository extends EloquentRepository implements FacebookMessageAttachmentRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return FacebookMessageAttachment::class;
    }

}