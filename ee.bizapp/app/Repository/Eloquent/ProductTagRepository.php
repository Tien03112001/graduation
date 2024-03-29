<?php
/**
 * Created by PhpStorm.
 * ProductTag: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\ProductTag;
use App\Repository\ProductTagRepositoryInterface;

class ProductTagRepository extends EloquentRepository implements ProductTagRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return ProductTag::class;
    }

}