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
use Illuminate\Database\Eloquent\Model;

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

    /**
     * @param ProductTag|\Illuminate\Database\Eloquent\Model $tag
     * @param array $productIds
     * @return mixed
     */
    public function syncProducts($tag, array $productIds)
    {
        if ($tag instanceof ProductTag) {
            return $tag->products()->sync($productIds);
        }
        return false;

    }

    /**
     * @param ProductTag|Model $tag
     * @param $productId
     * @return mixed
     */
    public function attachProduct($tag, $productId)
    {
        if ($tag instanceof ProductTag) {
            return $tag->products()->attach($productId);
        }
        return false;
    }

    /**
     * @param ProductTag|Model $tag
     * @param $productId
     * @return mixed
     */
    public function detachProduct($tag, $productId)
    {
        if ($tag instanceof ProductTag) {
            return $tag->products()->detach($productId);
        }
        return false;
    }
}