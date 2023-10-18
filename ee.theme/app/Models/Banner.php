<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Banner
 *
 * @property int $id
 * @property int $group_id
 * @property string $name
 * @property string $image
 * @property int $order
 * @property string|null $alt
 * @property string|null $href
 * @property string|null $summary
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BannerGroup|null $group
 * @method static \Illuminate\Database\Eloquent\Builder|Banner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Banner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Banner query()
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereHref($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Banner extends Model
{
    protected $table = 'banners';
    protected $guarded = [];

    protected $appends = [
    ];

    public function group()
    {
        return $this->belongsTo(BannerGroup::class, 'group_id');
    }
}
