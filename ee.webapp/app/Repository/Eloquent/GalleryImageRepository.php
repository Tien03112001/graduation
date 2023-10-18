<?php
/**
 * Created by PhpStorm.
 * GalleryImage: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\GalleryImage;
use App\Repository\GalleryImageRepositoryInterface;

class GalleryImageRepository extends EloquentRepository implements GalleryImageRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return GalleryImage::class;
    }

}