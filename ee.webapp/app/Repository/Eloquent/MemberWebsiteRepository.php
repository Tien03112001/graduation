<?php
/**
 * Created by PhpStorm.
 * MemberWebsite: BaoHoang
 * Date: 6/5/2022
 * Time: 20:48
 */

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Common\WhereClause;
use App\Models\MemberWebsite;
use App\Repository\MemberWebsiteRepositoryInterface;

class MemberWebsiteRepository extends EloquentRepository implements MemberWebsiteRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return MemberWebsite::class;
    }

    public function getWebsiteIdsByMemberId(int $memberId)
    {
        $rows = $this->get([
            WhereClause::query('member_id', $memberId)
        ], 'member_id:desc');
        $websiteIds = [];
        foreach ($rows as $row) {
            array_push($websiteIds, $row->website_id);
        }
        return $websiteIds;
    }
}