<?php
/**
 * Created by PhpStorm.
 * Product: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Product;
use App\Repository\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class ProductRepository extends EloquentRepository implements ProductRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Product::class;
    }

    /**
     * @param Product|Model $product
     * @param array $tagIds
     * @return mixed
     */
    public function syncTags($product, array $tagIds)
    {
        if ($product instanceof Product) {
            return $product->tags()->sync($tagIds);
        }
        return false;
    }

    /**
     * @param Product|Model $product
     * @param $tagId
     * @return mixed
     */
    public function attachTag($product, $tagId)
    {
        if ($product instanceof Product) {
            return $product->tags()->attach($tagId);
        }
        return false;
    }

    /**
     * @param Product|Model $product
     * @param $tagId
     * @return mixed
     */
    public function detachTag($product, $tagId)
    {
        if ($product instanceof Product) {
            return $product->tags()->detach($tagId);
        }
        return false;
    }
}