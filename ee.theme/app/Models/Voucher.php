<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Voucher
 *
 * @property int $id
 * @property string $code
 * @property int $quantity
 * @property int $remain_quantity
 * @property float $min_order_value
 * @property int $min_products_count
 * @property float $discount_value
 * @property float $discount_percent
 * @property int $enable
 * @property string $expired_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher query()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereDiscountPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereDiscountValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereEnable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereExpiredDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereMinOrderValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereMinProductsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereRemainQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereName($value)
 * @property int $free_ship
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereFreeShip($value)
 */
class Voucher extends Model
{
    use HasFactory;

    protected $casts = [
        'enable' => 'boolean'
    ];
}
