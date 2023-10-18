<?php

namespace App\Models;

use App\Utils\StringUtil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductTag
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $summary
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTag query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTag whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTag whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTag whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Article|null $article
 * @property-read mixed $full_path
 * @property-read \App\Models\MetaData|null $meta
 */
class ProductTag extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $appends = [
        'full_path',
    ];

    //Attributes
    public function getFullPathAttribute()
    {
        return StringUtil::joinPaths(
            config('app.url'),
            'product_tags', $this->attributes['slug']
        );
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tag_mapping', 'tag_id', 'product_id');
    }

    public function article()
    {
        return $this->morphOne(Article::class, 'articleable');
    }

    public function meta()
    {
        return $this->morphOne(MetaData::class, 'metaable');
    }
}
