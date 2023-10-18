<?php
/**
 * Created by PhpStorm.
 * ProductTag: BaoHoang
 * Date: 6/5/2022
 * Time: 20:49
 */

namespace App\Repository;


use App\Common\RepositoryInterface;
use App\Models\ProductTag;
use Illuminate\Database\Eloquent\Model;

interface ProductTagRepositoryInterface extends RepositoryInterface
{
    /**
     * @param ProductTag|Model $tag
     * @param array $productIds
     * @return mixed
     */
    public function syncProducts($tag, array $productIds);

    /**
     * @param ProductTag|Model $tag
     * @param $productId
     * @return mixed
     */
    public function attachProduct($tag, $productId);

    /**
     * @param ProductTag|Model $tag
     * @param $productId
     * @return mixed
     */
    public function detachProduct($tag, $productId);
}