<?php
/**
 * Created by PhpStorm.
 * Post: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Common\Exceptions\ObjectNotFoundException;
use App\Models\Post;
use App\Repository\PostRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PostRepository extends EloquentRepository implements PostRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Post::class;
    }

    public function create(array $attributes, $with = [], $withCount = [])
    {
        if (!($this->_model instanceof Post)) {
            return null;
        }
        $article = $attributes['article'];
        unset($attributes['article']);
        $model = $this->_model->create($attributes);
        if (isset($article)) {
            $model->article()->create($article);
        }
        if ($model) {
            if (!empty($with)) {
                $model->load($with);
            }

            if (!empty($withCount)) {
                $model->loadCount($withCount);
            }
            return $model;
        } else {
            return null;
        }
    }

    public function delete($id, $with = [])
    {
        $model = $id;
        if (is_numeric($id)) {
            $model = $this->findById($id, ['article']);
        }
        if ($model && $model instanceof Post) {
            $this->_model->where('order', '>', $model->order)->update([
                'order' => DB::raw('`order` - 1')
            ]);
            $model->article()->delete();
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
     * @param $id
     * @param $tagIds
     * @return \Illuminate\Database\Eloquent\Model
     * @throws ObjectNotFoundException
     */
    public function syncTags($id, $tagIds)
    {
        $model = $id;
        if (is_numeric($id)) {
            $model = $this->findById($id);
        }

        if (!($model instanceof Post)) {
            throw new ObjectNotFoundException();
        }

        if ($model) {
            $model->tags()->sync($tagIds);
            $model->load('tags');
            return $model;
        } else {
            throw new ObjectNotFoundException();
        }
    }

    /**
     * @param Post|Model $model
     * @param $tagId
     * @return mixed
     */
    public function attachTag($model, $tagId)
    {
        if ($model instanceof Post) {
            return $model->tags()->attach($tagId);
        }
        return false;
    }

    /**
     * @param Post|Model $model
     * @param $tagId
     * @return mixed
     */
    public function detachTag($model, $tagId)
    {
        if ($model instanceof Post) {
            return $model->tags()->detach($tagId);
        }
        return false;
    }

}