<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderTransactionPayment
 *
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransactionPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransactionPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransactionPayment query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $order_id
 * @property string $code
 * @property string $payer_name
 * @property string|null $payer_email
 * @property string|null $payer_phone
 * @property float $amount
 * @property string $currency
 * @property string $method
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransactionPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransactionPayment whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransactionPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransactionPayment whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransactionPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransactionPayment whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransactionPayment whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransactionPayment wherePayerEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransactionPayment wherePayerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransactionPayment wherePayerPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransactionPayment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransactionPayment whereUpdatedAt($value)
 */
class OrderTransactionPayment extends Model
{
    use HasFactory;
    protected $guarded = [];
}
