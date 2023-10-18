<?php
/**
 * Created by PhpStorm.
 * DynamicTable: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Common\Enum\EziWebRemoteCacheTypeEnum;
use App\Models\DynamicTable;
use App\Repository\DynamicTableRepositoryInterface;

class DynamicTableRepository extends EloquentRepository implements DynamicTableRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return DynamicTable::class;
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
        $result = parent::delete($id, $with = []);
        return $result;
    }
}