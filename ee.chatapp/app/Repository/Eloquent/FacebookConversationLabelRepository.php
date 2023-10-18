<?php
/**
 * Created by PhpStorm.
 * FacebookConversationLabel: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\FacebookConversationLabel;
use App\Repository\FacebookConversationLabelRepositoryInterface;

class FacebookConversationLabelRepository extends EloquentRepository implements FacebookConversationLabelRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return FacebookConversationLabel::class;
    }

}