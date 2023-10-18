<?php

namespace App\Models;

use App\Utils\StringUtil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductCategory
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int|null $parent_id
 * @property int $published
 * @property string|null $summary
 * @property string|null $image
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $full_path
 * @property-read ProductCategory|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Article|null $article
 * @property-read \App\Models\MetaData|null $meta
 */
class ProductCategory extends Model
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
            'product_categories', isset($this->attributes['slug'])?$this->attributes['slug']:'tat-ca'
        );
    }

    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    public function article()
    {
        return $this->morphOne(Article::class, 'articleable');
    }

    public function meta()
    {
        return $this->morphOne(MetaData::class, 'metaable');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
