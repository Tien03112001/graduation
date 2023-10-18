<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MetaData
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string|null $keywords
 * @property string|null $robots
 * @property string|null $canonical
 * @property string|null $more
 * @property int $metaable_id
 * @property string $metaable_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData query()
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData whereCanonical($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData whereMetaableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData whereMetaableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData whereMore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData whereRobots($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class MetaData extends Model
{
    protected $table = 'meta_data';
    protected $guarded = [];
}
