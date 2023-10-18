<?php
/**
 * Created by PhpStorm.
 * Banner: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Banner;
use App\Repository\BannerRepositoryInterface;

class BannerRepository extends EloquentRepository implements BannerRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Banner::class;
    }

}