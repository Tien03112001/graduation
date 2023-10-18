<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GProduct
 *
 * @property int $id
 * @property int $product_id
 * @property int $enable
 * @property string $gid
 * @property string $title
 * @property string $description
 * @property string $link
 * @property string $image_link
 * @property string $additional_image_link
 * @property string $availability
 * @property string $availability_​​date
 * @property float $price
 * @property float $sale_price
 * @property string $google_product_category
 * @property string $product_type
 * @property string $brand
 * @property string $gtin
 * @property string $mpn
 * @property string $condition
 * @property string $adult
 * @property string $age_group
 * @property string $color
 * @property string $gender
 * @property string $material
 * @property string $pattern
 * @property string $size
 * @property string $size_type
 * @property string $item_group_id
 * @property string $custom_label_0
 * @property string $custom_label_1
 * @property string $custom_label_2
 * @property string $custom_label_3
 * @property string $custom_label_4
 * @property string $promotion_id
 * @property string $currency
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereAdditionalImageLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereAdult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereAgeGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereAvailability($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereAvailability​​date($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereCustomLabel0($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereCustomLabel1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereCustomLabel2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereCustomLabel3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereCustomLabel4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereEnable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereGid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereGoogleProductCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereGtin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereImageLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereItemGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereMaterial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereMpn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct wherePattern($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereProductType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct wherePromotionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereSalePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereSizeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereAvailability​​date($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereAvailability​​date($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GProduct whereAvailability​​date($value)
 */
class GProduct extends Model
{
    use HasFactory;
    protected $guarded = [];
}
