<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FProduct
 *
 * @property int $id
 * @property int $product_id
 * @property int $enable
 * @property string $fid
 * @property string $title
 * @property string $description
 * @property string $availability
 * @property string $condition
 * @property float $price
 * @property string $link
 * @property string $image_link
 * @property string $brand
 * @property int $quantity_to_sell_on_facebook
 * @property string $fb_product_category
 * @property string $google_product_category
 * @property string $size
 * @property float $sale_price
 * @property string|null $sale_price_effective_date
 * @property string $item_group_id
 * @property string $status
 * @property string $additional_image_link
 * @property string $color
 * @property string $gender
 * @property string $material
 * @property string $pattern
 * @property string $custom_label_0
 * @property string $custom_label_1
 * @property string $custom_label_2
 * @property string $custom_label_3
 * @property string $custom_label_4
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereAdditionalImageLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereAvailability($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereCustomLabel0($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereCustomLabel1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereCustomLabel2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereCustomLabel3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereCustomLabel4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereEnable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereFbProductCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereFid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereGoogleProductCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereImageLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereItemGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereMaterial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct wherePattern($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereQuantityToSellOnFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereSalePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereSalePriceEffectiveDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FProduct whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FProduct extends Model
{
    use HasFactory;
    protected $guarded = [];
}
