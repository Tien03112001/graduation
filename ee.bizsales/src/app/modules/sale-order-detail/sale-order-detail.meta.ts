import {ProductMeta} from '../product/product.meta';
import {ProductVariantMeta} from '../product-variant/product-variant.meta';

export class SaleOrderDetailMeta {
  id: number;
  order_id: number;
  detail_code: string;
  product_id: number;
  variant_id: number;
  product_code: string;
  size: string;
  quantity: number;
  unit_price: number;
  amount: number;
  variant: ProductVariantMeta;
  product: ProductMeta;
}
