<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ConversationSession
 *
 * @property int $id
 * @property int $conversation_id
 * @property int $agent_id
 * @property bool $open_status
 * @property string|null $expired_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ConversationSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConversationSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConversationSession query()
 * @method static \Illuminate\Database\Eloquent\Builder|ConversationSession whereAgentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversationSession whereConversationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversationSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversationSession whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversationSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversationSession whereOpenStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversationSession whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ConversationSession extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'open_status' => 'boolean'
    ];
}
