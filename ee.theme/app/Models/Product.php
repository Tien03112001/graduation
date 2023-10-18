<?php

namespace App\Models;

use App\Utils\StringUtil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property int $category_id
 * @property string $category_slug
 * @property string $name
 * @property string $code
 * @property string $slug
 * @property string|null $image
 * @property string|null $summary
 * @property float $sale_price
 * @property float $price
 * @property int $order
 * @property int $published
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductAttribute[] $additional_attributes
 * @property-read int|null $additional_attributes_count
 * @property-read \App\Models\ProductCategory|null $category
 * @property-read mixed $full_path
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RelatedProduct[] $related_products
 * @property-read int|null $related_products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductTag[] $tags
 * @property-read int|null $tags_count
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCategorySlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSalePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Article|null $article
 * @property-read \App\Models\MetaData|null $meta
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StructureData[] $structured_datas
 * @property-read int|null $structured_datas_count
 * @property string $sku
 * @property string $brand
 * @property string $item_condition
 * @property string $availability
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAvailability($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereItemCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSku($value)
 */
class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'full_path',
    ];

    //Attributes
    public function getFullPathAttribute()
    {
        return StringUtil::joinPaths(config('app.url'),
            'product_categories', $this->attributes['category_slug'],
            'products', $this->attributes['slug']
        );
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function additional_attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function related_products()
    {
        return $this->hasMany(RelatedProduct::class);
    }

    public function tags()
    {
        return $this->belongsToMany(ProductTag::class, 'product_tag_mapping', 'product_id', 'tag_id');
    }

    public function article()
    {
        return $this->morphOne(Article::class, 'articleable');
    }

    public function meta()
    {
        return $this->morphOne(MetaData::class, 'metaable');
    }

    public function structured_datas()
    {
        return $this->morphMany(StructureData::class, 'structureble');
    }

    public function promotions()
    {
        return $this->belongsToMany(Promotion::class, 'promotion_product_mapping');
    }

    public function comments()
    {
        return $this->hasManyThrough(Comment::class, Article::class, 'articleable_id')
            ->where('articleable_type', array_search(static::class, Relation::morphMap()) ?: static::class);
    }

    public function public_comments()
    {
        return $this->comments()->where('published', true);
    }


    public function gallery_images()
    {
        return $this->hasManyThrough(GalleryImage::class, Gallery::class, 'galleryable_id')
            ->where('galleryable_type', array_search(static::class, Relation::morphMap()) ?: static::class);
    }

    public function public_gallery_images()
    {
        return $this->gallery_images()->where('published', true);
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class,'product_id');
    }
}
