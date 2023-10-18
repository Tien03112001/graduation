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

}