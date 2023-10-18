<?php
/**
 * Created by PhpStorm.
 * PostCategory: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Common\Exceptions\ObjectNotFoundException;
use App\Models\PostCategory;
use App\Repository\PostCategoryRepositoryInterface;

class PostCategoryRepository extends EloquentRepository implements PostCategoryRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return PostCategory::class;
    }

    public function delete($id, $with = [])
    {
        $model = $id;
        if (is_numeric($id)) {
            $model = $this->findById($id);
        }
        if ($model && $model instanceof PostCategory) {
            $model->posts()->delete();
            if ($model->delete()) {
                return true;
            } else {
                throw new \Exception('Không thể xóa');
            }
        } else {
            throw new ObjectNotFoundException();
        }
    }

}