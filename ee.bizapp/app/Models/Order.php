<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $connection = 'web_system';
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

    protected $guarded = [];

    public function order_details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function province() {
        return $this->hasOne(ShippingProvince::class,'id', 'province_id');
    }

    public function district() {
        return $this->hasOne(ShippingDistrict::class,'id', 'district_id');
    }

    public function ward() {
        return $this->hasOne(ShippingWard::class,'id', 'ward_id');
    }
}
