import {OrderProductMeta} from '../order-product/order-product.meta';

export class OrderMeta {
  id: number;
  code: string;
  customer_name: string;
  customer_phone: string;
  customer_address: string;
  customer_email: string;
  payment_type: string;
  total_amount: number;
  payment_status: string;
  order_status: string;
  created_at: string;
  order_details: OrderProductMeta[];
}
