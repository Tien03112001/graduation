<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Message
 *
 * @property int $id
 * @property int $conversation_id
 * @property int $session_id
 * @property int $agent_id
 * @property string $sender_name
 * @property int $type
 * @property string|null $content
 * @property string|null $source_type
 * @property int|null $source_id
 * @property bool $sent_status
 * @property int $opened_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $source
 * @property-read \App\Models\Conversation|\Eloquent $conversation
 * @property-read \App\Models\MessageAttachment[]|\Eloquent[] $attachments
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereAgentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereConversationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereOpenedStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSenderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSourceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Message extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'sent_status' => 'boolean',
        'open_status' => 'boolean',
    ];

    public function source()
    {
        return $this->morphTo('source');
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function attachments()
    {
        return $this->hasMany(MessageAttachment::class);
    }

}
