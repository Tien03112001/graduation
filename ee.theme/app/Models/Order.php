<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property string $code
 * @property string $customer_name
 * @property string $customer_phone
 * @property string $customer_address
 * @property string $customer_email
 * @property int $province_id
 * @property int $district_id
 * @property int $ward_id
 * @property string $payment_type
 * @property float $amount
 * @property float $shipping_fee
 * @property float $vat
 * @property float $total_amount
 * @property string $date_at
 * @property string|null $request
 * @property string $payment_status
 * @property string $order_status
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCustomerAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCustomerEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCustomerPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDateAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDistrictId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShippingFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereWardId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderItem[] $items
 * @property-read int|null $items_count
 * @property float $discount
 * @property-read \App\Models\PaymentMethod|null $payment_method
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Voucher[] $vouchers
 * @property-read int|null $vouchers_count
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDiscount($value)
 * @property-read \App\Models\PaymentTransaction|null $transaction
 */
class Order extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class, 'order_voucher_mapping');
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_type', 'name');
    }

    public function transaction()
    {
        return $this->hasOne(PaymentTransaction::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}