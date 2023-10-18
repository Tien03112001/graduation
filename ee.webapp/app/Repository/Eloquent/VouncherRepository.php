<?php
/**
 * Created by PhpStorm.
 * Vouncher: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Vouncher;
use App\Repository\VouncherRepositoryInterface;

class VouncherRepository extends EloquentRepository implements VouncherRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Vouncher::class;
    }

}