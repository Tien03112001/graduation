<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RelatedProduct
 *
 * @property int $id
 * @property int $product_id
 * @property int $related_id
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\Product|null $relation
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedProduct whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedProduct whereRelatedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedProduct whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RelatedProduct extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function relation()
    {
        return $this->belongsTo(Product::class, 'related_id');
    }
}
