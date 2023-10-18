<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FacebookConversation
 *
 * @property int $id
 * @property int $page_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Conversation|null $conversation
 * @property-read \App\Models\FacebookFanpage|null $page
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookConversation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookConversation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookConversation query()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookConversation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookConversation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookConversation wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookConversation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookConversation whereUserId($value)
 * @mixin \Eloquent
 */
class FacebookConversation extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function conversation()
    {
        return $this->morphOne(Conversation::class, 'source');
    }

    public function page()
    {
        return $this->belongsTo(FacebookFanpage::class, 'page_id');
    }
}
