<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BannerGroup
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Banner[] $banners
 * @property-read int|null $banners_count
 * @method static \Illuminate\Database\Eloquent\Builder|BannerGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BannerGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BannerGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|BannerGroup whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BannerGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BannerGroup whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BannerGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BannerGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BannerGroup whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class BannerGroup extends Model
{
    protected $table = 'banner_groups';
    protected $guarded = [];

    public function banners()
    {
        return $this->hasMany(Banner::class, 'group_id');
    }

}
