<?php
/**
 * Created by PhpStorm.
 * Block: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Block;
use App\Repository\BlockRepositoryInterface;

class BlockRepository extends EloquentRepository implements BlockRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Block::class;
    }

}