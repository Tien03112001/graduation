<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductAttribute
 *
 * @property int $id
 * @property int $product_id
 * @property string $name
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereValue($value)
 * @mixin \Eloquent
 */
class ProductAttribute extends Model
{
    use HasFactory;
    protected $guarded = [];
}
