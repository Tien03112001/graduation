<?php
/**
 * Created by PhpStorm.
 * Gallery: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Common\Exceptions\ObjectNotFoundException;
use App\Models\Gallery;
use App\Repository\GalleryRepositoryInterface;

class GalleryRepository extends EloquentRepository implements GalleryRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Gallery::class;
    }

    /**
     * @param $id
     * @param array $with
     * @return bool
     * @throws ObjectNotFoundException
     * @throws \Exception
     */
    public function delete($id, $with = [])
    {
        $model = $id;
        if (is_numeric($id)) {
            $model = $this->findById($id);
        }
        if ($model) {
            if ($model instanceof Gallery) {
                $model->images()->delete();
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
}