<?php
/**
 * Created by PhpStorm.
 * ConversationLabel: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\ConversationLabel;
use App\Repository\ConversationLabelRepositoryInterface;

class ConversationLabelRepository extends EloquentRepository implements ConversationLabelRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return ConversationLabel::class;
    }

}