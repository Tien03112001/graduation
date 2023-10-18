<?php
/**
 * Created by PhpStorm.
 * FacebookPage: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\FacebookPage;
use App\Repository\FacebookPageRepositoryInterface;

class FacebookPageRepository extends EloquentRepository implements FacebookPageRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return FacebookPage::class;
    }

}