<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\MemberWebsite
 *
 * @property int $website_id
 * @property int $member_id
 * @method static \Illuminate\Database\Eloquent\Builder|MemberWebsite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberWebsite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberWebsite query()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberWebsite whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberWebsite whereWebsiteId($value)
 * @mixin \Eloquent
 */
class MemberWebsite extends Model
{
    use HasFactory;
    protected $table = 'member_website';
    protected $connection = 'system';
    protected $primaryKey = null;

    public function name()
    {
        return $this->rel(User::class);
    }
}
