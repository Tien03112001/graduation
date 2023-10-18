<?php
/**
 * Created by PhpStorm.
 * EmbedScript: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\EmbedScript;
use App\Repository\EmbedScriptRepositoryInterface;

class EmbedScriptRepository extends EloquentRepository implements EmbedScriptRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return EmbedScript::class;
    }

}