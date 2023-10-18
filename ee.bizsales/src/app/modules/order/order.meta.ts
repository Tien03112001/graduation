import {OrderDetailMeta} from '../order-detail/order-detail.meta';
import { ShippingDistrictMeta } from '../shipping-district/shipping-district.meta';
import { ShippingProvinceMeta } from '../shipping-province/shipping-province.meta';
import { ShippingWardMeta } from '../shipping-ward/shipping-ward.meta';

export class OrderMeta {
  id: number;
  code: string;
  customer_name: string;
  customer_phone: string;
  customer_address: boolean;
  province_id: number;
  district_id: number;
  ward_id: number;
  province: ShippingProvinceMeta[];
  district: ShippingDistrictMeta[];
  ward: ShippingWardMeta[];
  payment_type: string;
  amount: number;
  shipping_fee: number;
  vat: number;
  total_amount: number;
  request: string;
  payment_status: string;
  order_status: string;
  note: string;
  order_details: OrderDetailMeta[];

  public static $TRANG_THAI_LEN_DON = 'Lên đơn';
  public static $TRANG_THAI_XAC_THUC = 'Xác thực';
  public static $TRANG_THAI_HUY_DON = 'Hủy đơn';
  public static $TRANG_THAI_CHUAN_BI_HANG = 'Chuẩn bị hàng';
  public static $TRANG_THAI_CHO_HANG = 'Chờ hàng';
  public static $TRANG_THAI_DANG_GIAO = 'Đang giao';
  public static $TRANG_THAI_DA_GIAO = 'Đã giao';
  public static $TRANG_THAI_HOAN_THANH = 'Hoàn thành';
  public static $TRANG_THAI_KHIEU_NAI = 'Khiếu nại';
  public static $TRANG_THAI_DOI_HANG = 'Đổi hàng';
  public static $TRANG_THAI_NHAN_HANG_DOI = 'Đã nhận đổi';
  public static $TRANG_THAI_HOAN_VE = 'Hoàn về';
  public static $TRANG_THAI_CAP_NHAP_LAI = 'Cập nhập lại';
  public static $TRANG_THAI_DA_NHAN_HOAN = 'Đã nhận hoàn';
  public static $TRANG_THAI_DA_DOI_HANG = 'Đã đổi hàng';
  public static $TRANG_THAI_MAT_HANG = 'Mất hàng';
  public static $TRANG_THAI_HOAN_TIEN = 'Hoàn tiền';
  public static $TRANG_THAI_TRANH_CHAP = 'Tranh chấp';
  public static $TRANG_THAI_DA_HOAN_TIEN = 'Đã hoàn tiền';
}

