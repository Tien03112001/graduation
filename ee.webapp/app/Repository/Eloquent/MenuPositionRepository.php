<?php
/**
 * Created by PhpStorm.
 * MenuPosition: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Common\Exceptions\ObjectNotFoundException;
use App\Models\MenuPosition;
use App\Repository\MenuPositionRepositoryInterface;

class MenuPositionRepository extends EloquentRepository implements MenuPositionRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return MenuPosition::class;
    }

    public function create(array $attributes, $with = [], $withCount = [])
    {
        $result = parent::create($attributes, $with, $withCount);
        return $result;
    }

    public function update($id, array $attributes, $with = [], $withCount = [])
    {
        $result = parent::update($id, $attributes, $with, $withCount);
        return $result;
    }

    public function delete($id, $with = [])
    {
        $model = $id;
        if (is_numeric($id)) {
            $model = $this->findById($id);
        }
        if ($model && $model instanceof MenuPosition) {
            $model->menus()->delete();
            $model->delete();
            return true;
        } else {
            throw new ObjectNotFoundException();
        }
    }
}