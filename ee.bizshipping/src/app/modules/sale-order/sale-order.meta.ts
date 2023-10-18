import {SaleOrderHistoryMeta} from '../sale-order-history/sale-order-history.meta';
import {UserMeta} from '../user/user.meta';
import {SaleOrderDetailMeta} from '../sale-order-detail/sale-order-detail.meta';
import {SaleOrderShippingMeta} from '../sale-order-shipping/sale-order-shipping.meta';

export class SaleOrderMeta {
  id: number;
  seller_id: string;
  code: string;
  channel: string;

  customer_name: string;
  customer_phone: string;
  customer_address: boolean;
  customer_request: string;

  amount: number;
  ship_fee: number;
  total_amount: number;
  cod_fee: number;


  payment_type: number;
  pre_charged: number;
  banking_sms: string;

  status: string;
  is_completed: boolean;



  details: SaleOrderDetailMeta[];
  histories: SaleOrderHistoryMeta;
  seller: UserMeta;
  shipping: SaleOrderShippingMeta;

  province: string;
  district: string;
  ward: string;

  details_text: string;
  customer_text: string;

  note: string;
  note_ship: string;
  created_at: string;
  updated_at: string;

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

  public static LIST: any[] = [
    {text: SaleOrderMeta.$TRANG_THAI_LEN_DON, value: SaleOrderMeta.$TRANG_THAI_LEN_DON},
    {text: SaleOrderMeta.$TRANG_THAI_XAC_THUC, value: SaleOrderMeta.$TRANG_THAI_XAC_THUC},
    {text: SaleOrderMeta.$TRANG_THAI_HUY_DON, value: SaleOrderMeta.$TRANG_THAI_HUY_DON},
    {text: SaleOrderMeta.$TRANG_THAI_CHUAN_BI_HANG, value: SaleOrderMeta.$TRANG_THAI_CHUAN_BI_HANG},
    {text: SaleOrderMeta.$TRANG_THAI_CHO_HANG, value: SaleOrderMeta.$TRANG_THAI_CHO_HANG},
    {text: SaleOrderMeta.$TRANG_THAI_DANG_GIAO, value: SaleOrderMeta.$TRANG_THAI_DANG_GIAO},
    {text: SaleOrderMeta.$TRANG_THAI_DA_GIAO, value: SaleOrderMeta.$TRANG_THAI_DA_GIAO},
    {text: SaleOrderMeta.$TRANG_THAI_HOAN_THANH, value: SaleOrderMeta.$TRANG_THAI_HOAN_THANH},
    {text: SaleOrderMeta.$TRANG_THAI_KHIEU_NAI, value: SaleOrderMeta.$TRANG_THAI_KHIEU_NAI},
    {text: SaleOrderMeta.$TRANG_THAI_DOI_HANG, value: SaleOrderMeta.$TRANG_THAI_DOI_HANG},
    {text: SaleOrderMeta.$TRANG_THAI_NHAN_HANG_DOI, value: SaleOrderMeta.$TRANG_THAI_NHAN_HANG_DOI},
    {text: SaleOrderMeta.$TRANG_THAI_DA_DOI_HANG, value: SaleOrderMeta.$TRANG_THAI_DA_DOI_HANG},
    {text: SaleOrderMeta.$TRANG_THAI_HOAN_VE, value: SaleOrderMeta.$TRANG_THAI_HOAN_VE},
    {text: SaleOrderMeta.$TRANG_THAI_CAP_NHAP_LAI, value: SaleOrderMeta.$TRANG_THAI_CAP_NHAP_LAI},
    {text: SaleOrderMeta.$TRANG_THAI_DA_NHAN_HOAN, value: SaleOrderMeta.$TRANG_THAI_DA_NHAN_HOAN},
    {text: SaleOrderMeta.$TRANG_THAI_MAT_HANG, value: SaleOrderMeta.$TRANG_THAI_MAT_HANG},
    {text: SaleOrderMeta.$TRANG_THAI_HOAN_TIEN, value: SaleOrderMeta.$TRANG_THAI_HOAN_TIEN},
    {text: SaleOrderMeta.$TRANG_THAI_TRANH_CHAP, value: SaleOrderMeta.$TRANG_THAI_TRANH_CHAP},
    {text: SaleOrderMeta.$TRANG_THAI_DA_HOAN_TIEN, value: SaleOrderMeta.$TRANG_THAI_DA_HOAN_TIEN},
  ];

}

