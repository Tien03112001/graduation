<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FacebookMessageAttachment
 *
 * @property int $id
 * @property int $message_id
 * @property string $type
 * @property string|null $url
 * @property string|null $title
 * @property string|null $sticker_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessageAttachment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessageAttachment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessageAttachment query()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessageAttachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessageAttachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessageAttachment whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessageAttachment whereStickerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessageAttachment whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessageAttachment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessageAttachment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookMessageAttachment whereUrl($value)
 * @mixin \Eloquent
 */
class FacebookMessageAttachment extends Model
{
    use HasFactory;
    protected $guarded = [];
}
