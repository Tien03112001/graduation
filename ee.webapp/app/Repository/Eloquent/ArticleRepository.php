<?php
/**
 * Created by PhpStorm.
 * Article: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Article;
use App\Repository\ArticleRepositoryInterface;

class ArticleRepository extends EloquentRepository implements ArticleRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Article::class;
    }

}