import { ProductVariantMeta } from '../product-variant/product-variant.meta';
import {ProductMeta} from '../product/product.meta';

export class OrderDetailMeta {
  id: number;
  order_id: number;
  product_id: number;
  product_name: string;
  price: number;
  quantity: number;
  amount: number;
  sub_total: number;
  variant_id: number;
  product: ProductMeta;
  variant: ProductVariantMeta;
}

