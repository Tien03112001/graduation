<?php
/**
 * Created by PhpStorm.
 * RelatedPost: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\RelatedPost;
use App\Repository\RelatedPostRepositoryInterface;

class RelatedPostRepository extends EloquentRepository implements RelatedPostRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return RelatedPost::class;
    }

}