<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use App\Utils\StringUtil;
use Illuminate\Database\Eloquent\Relations\Relation;

class Product extends Model
{
    protected $connection = 'web_system';
    protected $table = 'products';
    
    protected $appends = [
        'full_path',
    ];

    public function gallery()
    {
        return $this->morphOne(Gallery::class, 'galleryable');
    }

    public function inventories()
    {
        return $this->hasMany(InventoryProduct::class, 'product_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(ProductTag::class, 'product_tag_mapping', 'product_id', 'tag_id');
    }

    public function article()
    {
        return $this->morphOne(Article::class, 'articleable');
    }

    public function promotions()
    {
        return $this->belongsToMany(Promotion::class, 'promotion_product_mapping');
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

    public function getFullPathAttribute()
    {
        return StringUtil::joinPaths( Config::get('app.web_url'),
            'product_categories', $this->attributes['category_slug'],
            'products', $this->attributes['slug']
        );
    }
}
