<?php
/**
 * Created by PhpStorm.
 * Promotion: BaoHoang
 * Date: 6/5/2022
 * Time: 20:49
 */

namespace App\Repository;


use App\Common\RepositoryInterface;
use App\Models\Promotion;
use Illuminate\Database\Eloquent\Model;

interface PromotionRepositoryInterface extends RepositoryInterface
{
    /**
     * @param Promotion|Model $model
     * @param array $productIds
     * @return mixed
     */
    public function syncProducts($model, array $productIds);


    /**
     * @param Promotion|Model $model
     * @param $productId
     * @return mixed
     */
    public function   attachProduct($model, $productId);

    /**
     * @param @param Promotion|Model $model
     * @param $productId
     * @return mixed
     */
    public function detachProduct($model, $productId);
}
