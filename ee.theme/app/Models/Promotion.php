<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Promotion
 *
 * @property int $id
 * @property float $min_order_value
 * @property int $min_products_count
 * @property float $discount_value
 * @property float $discount_percent
 * @property int $enable
 * @property string $expired_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion query()
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereDiscountPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereDiscountValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereEnable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereExpiredDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereMinOrderValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereMinProductsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion whereName($value)
 */
class Promotion extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'enable' => 'boolean'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'promotion_product_mapping');
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
