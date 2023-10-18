<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FacebookFanpage
 *
 * @property int $id
 * @property string $name
 * @property string $access_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookFanpage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookFanpage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookFanpage query()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookFanpage whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookFanpage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookFanpage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookFanpage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookFanpage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FacebookFanpage extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function agents()
    {
        return $this->belongsToMany(Agent::class, 'agent_page', 'page_id', 'agent_id');
    }
}
