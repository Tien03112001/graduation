<?php
/**
 * Created by PhpStorm.
 * Gallery: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
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

}