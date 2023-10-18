<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Agent
 *
 * @property int $id
 * @property int $user_id
 * @property bool $is_online
 * @property string|null $online_at
 * @property int $priority
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Agent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agent query()
 * @method static \Illuminate\Database\Eloquent\Builder|Agent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent whereIsOnline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent whereOnlineAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FacebookFanpage[] $pages
 * @property-read int|null $pages_count
 */
class Agent extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'is_online' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(AgentAccount::class);
    }

    public function pages()
    {
        return $this->belongsToMany(FacebookFanpage::class, 'agent_page', 'agent_id', 'page_id');
    }
}
