<?php
/**
 * Created by PhpStorm.
 * Product: BaoHoang
 * Date: 6/5/2022
 * Time: 20:49
 */

namespace App\Repository;


use App\Common\RepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

interface ProductRepositoryInterface extends RepositoryInterface
{
    /**
     * @param Product|Model $product
     * @param array $tagIds
     * @return mixed
     */
    public function syncTags($product, array $tagIds);


    /**
     * @param Product|Model $product
     * @param $tagId
     * @return mixed
     */
    public function attachTag($product, $tagId);

    /**
     * @param Product|Model $product
     * @param $tagId
     * @return mixed
     */
    public function detachTag($product, $tagId);
}