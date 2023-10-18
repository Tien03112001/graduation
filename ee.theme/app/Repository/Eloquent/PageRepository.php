<?php
/**
 * Created by PhpStorm.
 * Page: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Page;
use App\Repository\PageRepositoryInterface;

class PageRepository extends EloquentRepository implements PageRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Page::class;
    }

}