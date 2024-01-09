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
 * @property float $discount
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDiscount($value)
 */
class Order extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'orders';

    public static $TRANG_THAI_LEN_DON = "Lên đơn";
    public static $TRANG_THAI_XAC_THUC = "Xác thực";

    public static $TRANG_THAI_HUY_DON = "Hủy đơn";
    public static $TRANG_THAI_CAP_NHAP_LAI = "Cập nhập lại";

    public static $TRANG_THAI_CHUAN_BI_HANG = "Chuẩn bị hàng";

    public static $TRANG_THAI_CHO_HANG = "Chờ hàng";

    public static $TRANG_THAI_DANG_GIAO = "Đang giao";
    public static $TRANG_THAI_DA_GIAO = "Đã giao";

    public static $TRANG_THAI_HOAN_THANH = "Hoàn thành";

    public static $TRANG_THAI_KHIEU_NAI = "Khiếu nại";

    public static $TRANG_THAI_DOI_HANG = "Đổi hàng";
    public static $TRANG_THAI_NHAN_HANG_DOI = "Đã nhận đổi";
    public static $TRANG_THAI_DA_DOI_HANG = "Đã đổi hàng";

    public static $TRANG_THAI_HOAN_VE = "Hoàn về";
    public static $TRANG_THAI_DA_NHAN_HOAN = "Đã nhận hoàn";
    public static $TRANG_THAI_MAT_HANG = "Mất hàng";

    public static $TRANG_THAI_HOAN_TIEN = "Hoàn tiền";
    public static $TRANG_THAI_DA_HOAN_TIEN = "Đã hoàn tiền";

    public static $TRANG_THAI_TRANH_CHAP = "Tranh chấp";

    public function order_details()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function province() {
        return $this->hasOne(Province::class,'id', 'province_id');
    }

    public function district() {
        return $this->hasOne(District::class,'id', 'district_id');
    }

    public function ward() {
        return $this->hasOne(Ward::class,'id', 'ward_id');
    }
}
