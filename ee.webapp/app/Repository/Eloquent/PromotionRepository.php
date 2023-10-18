<?php
/**
 * Created by PhpStorm.
 * Promotion: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Promotion;
use App\Repository\PromotionRepositoryInterface;

class PromotionRepository extends EloquentRepository implements PromotionRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Promotion::class;
    }

    public function syncProducts($model, array $productIds)
    {
        if ($model instanceof Promotion) {
            return $model->products()->sync($productIds);
        }
        return false;
    }

    public function attachProduct($model, $productId)
    {
        if ($model instanceof Promotion) {
            if(!$model->products->find($productId))
            {
                return $model->products()->attach($productId);
            }
        }
        return false;
    }

    public function detachProduct($model, $productId)
    {
        if ($model instanceof Promotion) {
            return $model->products()->detach($productId);
        }
        return false;
    }

}
