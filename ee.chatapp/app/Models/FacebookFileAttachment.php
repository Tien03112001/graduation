<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FacebookFileAttachment
 *
 * @property int $id
 * @property int $collection_id
 * @property int $page_id
 * @property string $type
 * @property string $url
 * @property string|null $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\FacebookFanpage|null $page
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookFileAttachment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookFileAttachment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookFileAttachment query()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookFileAttachment whereCollectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookFileAttachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookFileAttachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookFileAttachment wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookFileAttachment whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookFileAttachment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookFileAttachment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookFileAttachment whereUrl($value)
 * @mixin \Eloquent
 */
class FacebookFileAttachment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function page()
    {
        return $this->belongsTo(FacebookFanpage::class, 'page_id');
    }

}
