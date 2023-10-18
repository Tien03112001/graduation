import {ProductMeta} from '../product/product.meta';
import { VariantMeta } from '../variant/variant.meta';

export class SaleOrderDetailMeta {
  id: number;
  order_id: number;
  detail_code: string;
  product_id: number;
  product_code: string;
  variant_id: number;
  size: string;
  quantity: number;
  unit_price: number;
  amount: number;
  variant: VariantMeta;
  product: ProductMeta;
}
