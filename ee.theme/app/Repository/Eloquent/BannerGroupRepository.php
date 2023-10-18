<?php
/**
 * Created by PhpStorm.
 * BannerGroup: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\BannerGroup;
use App\Repository\BannerGroupRepositoryInterface;

class BannerGroupRepository extends EloquentRepository implements BannerGroupRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return BannerGroup::class;
    }

}