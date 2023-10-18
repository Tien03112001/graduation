<?php
/**
 * Created by PhpStorm.
 * Product: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Common\Exceptions\ObjectNotFoundException;
use App\Models\Product;
use App\Common\Exceptions\RelationNotFoundException;
use App\Repository\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function delete($id, $with = [])
    {
        $model = $id;
        if (is_numeric($id)) {
            $model = $this->findById($id);
        }
        if ($model) {
            $this->_model->where('order', '>', $model->order)->update([
                'order' => DB::raw('`order` - 1')
            ]);
            if (!empty($with)) {
                foreach ($with as $relation) {
                    if ($model->isRelation($relation)) {
                        $model->{$relation}()->delete();
                    } else {
                        throw new RelationNotFoundException();
                    }
                }
            }
            if ($model->delete()) {
                return true;
            } else {
                throw new \Exception('Không thể xóa');
            }
        } else {
            throw new ObjectNotFoundException();
        }
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
