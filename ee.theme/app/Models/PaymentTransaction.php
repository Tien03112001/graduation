<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PaymentTransaction
 *
 * @property int $id
 * @property int $order_id
 * @property int $method_id
 * @property string $code
 * @property string $redirect_url
 * @property int $status
 * @property string|null $message
 * @property string|null $dump_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentTransaction whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentTransaction whereDumpData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentTransaction whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentTransaction whereMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentTransaction whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentTransaction whereRedirectUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentTransaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentTransaction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PaymentTransaction extends Model
{
    use HasFactory;
    protected $guarded = [];
}
