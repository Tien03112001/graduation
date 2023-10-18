<?php
/**
 * Created by PhpStorm.
 * BannerGroup: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Common\Enum\EziWebRemoteCacheTypeEnum;
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

    public function create(array $attributes, $with = [], $withCount = [])
    {
        $result = parent::create($attributes, $with, $withCount);
        return $result;
    }

    public function update($id, array $attributes, $with = [], $withCount = [])
    {
        $result = parent::update($id, $attributes, $with, $withCount);
        return $result;
    }

    public function delete($id, $with = [])
    {
        $result = parent::delete($id, $with = []);
        return $result;
    }

}