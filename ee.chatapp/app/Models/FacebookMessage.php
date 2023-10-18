<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FacebookMessage
 *
 * @property int $id
 * @property int $conversation_id
 * @property string $mid
 * @property string $reply_to_mid
 * @property string $quick_reply
 * @property string $sender_name
 * @property int $sender_id
 * @property int $receiver_id
 * @property string|null $content
 * @property string|null $referral_product_id
 * @property int $sent_timestamp
 * @property int $sent_status
 * @property int|null $read_timestamp
 * @property int $read_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FacebookMessageAttachment[] $attachments
 * @property-read int|null $attachments_count
 * @property-read \App\Models\FacebookConversation|null $conversation
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessage whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessage whereConversationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessage whereMid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessage whereQuickReply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessage whereReadStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessage whereReadTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessage whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessage whereReferralProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessage whereReplyToMid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessage whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessage whereSenderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessage whereSentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessage whereSentTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FacebookMessage extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function conversation()
    {
        return $this->belongsTo(FacebookConversation::class);
    }

    public function attachments()
    {
        return $this->hasMany(FacebookMessageAttachment::class, 'message_id');
    }

    public function message()
    {
        return $this->morphOne(Message::class, 'source');
    }
}
