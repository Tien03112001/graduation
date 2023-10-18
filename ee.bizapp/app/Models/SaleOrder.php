<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleOrder extends Model
{
    public static $TRANG_THAI_LUU_NHAP = "Lưu nháp";

    public static $TRANG_THAI_LEN_DON = "Lên đơn";
    public static $TRANG_THAI_XAC_THUC = "Xác nhận";

    public static $TRANG_THAI_HUY_DON = "Hủy đơn";
    public static $TRANG_THAI_CAP_NHAP_LAI = "Cập nhập lại";

    public static $TRANG_THAI_CHUAN_BI_HANG = "Chuẩn bị hàng";
    public static $TRANG_THAI_DA_CHUAN_BI_HANG = "Đã chuẩn bị hàng";

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


    protected $guarded = [];

    protected $casts = [
        'is_completed' => 'boolean',
        'is_printed' => 'boolean'
    ];
    protected $appends = [
        'customer_text',
        'details_text'
    ];

    public function details()
    {
        return $this->hasMany(SaleOrderDetail::class, 'order_id');
    }

    public function seller()
    {
        return $this->belongsTo(Account::class, 'seller_id');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }


    public function histories()
    {
        return $this->hasMany(SaleOrderHistory::class, 'order_id');
    }

    public function shipping()
    {
        return $this->hasOne(SaleOrderShipping::class, 'order_id');
    }

    public function payment()
    {
        return $this->hasOne(PaymentType::class, 'id', 'payment_type');
    }

    public function getCustomerTextAttribute()
    {
        return join("\n", [
            $this->attributes['customer_name'],
            $this->attributes['customer_phone'],
            join(' - ', [$this->attributes['customer_address'], $this->ward, $this->district, $this->province]),
            $this->attributes['customer_request']]);
    }

    public function getDetailsTextAttribute()
    {
        $lines = [];
        foreach ($this->details as $detail) {
            array_push($lines, sprintf('%s %s %d %d', $detail->product_code, $detail->size, $detail->unit_price, $detail->quantity));
        }
        return join("\n", $lines);
    }

    public static function setDone(SaleOrder $order)
    {
        $order->status = 'Hoàn thành';
        if ($order->shipping) {
            SaleOrderShipping::setDone($order->shipping);
            $order->shipping->save();
        }
    }

    public static function setCanceled(SaleOrder $order)
    {
        $order->status = 'Hủy đơn';
        if ($order->shipping) {
            SaleOrderShipping::setCanceled($order->shipping);
            $order->shipping->save();
        }
    }

    public static function setReturn(SaleOrder $order)
    {
        $order->status = 'Hoàn hàng';
        if ($order->shipping) {
            SaleOrderShipping::setReturn($order->shipping);
            $order->shipping->save();
        }
    }

    public static function setReceivedRefund(SaleOrder $order)
    {
        $order->status = 'Đã hoàn về';
        if ($order->shipping) {
            SaleOrderShipping::setReceivedRefund($order->shipping);
            $order->shipping->save();
        }
    }

    public static function setReUpdated(SaleOrder $order)
    {
        $order->status = 'Cập nhật lại';
        if ($order->shipping) {
            $order->shipping()->delete();
        }
    }

}
