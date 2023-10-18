<?php
/**
 * Created by PhpStorm.
 * Comment: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Comment;
use App\Repository\CommentRepositoryInterface;

class CommentRepository extends EloquentRepository implements CommentRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Comment::class;
    }

}