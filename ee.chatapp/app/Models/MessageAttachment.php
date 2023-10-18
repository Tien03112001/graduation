<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MessageAttachment
 *
 * @property int $id
 * @property int $message_id
 * @property string $type
 * @property string $url
 * @property string|null $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FacebookMessageAttachment[] $attachments
 * @property-read int|null $attachments_count
 * @method static \Illuminate\Database\Eloquent\Builder|MessageAttachment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageAttachment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageAttachment query()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageAttachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageAttachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageAttachment whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageAttachment whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageAttachment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageAttachment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageAttachment whereUrl($value)
 * @mixin \Eloquent
 */
class MessageAttachment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function attachments()
    {
        return $this->hasMany(FacebookMessageAttachment::class, 'message_id');
    }
}
