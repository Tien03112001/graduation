import { ProductVariantMeta } from '../product-variant/product-variant.meta';
import {ProductMeta} from '../product/product.meta';

export class InventoryProductMeta {
  id: number;
  code: string;
  product_id: number;
  product_code: string;
  size: string;
  quantity: number;
  used_quantity: number;
  available_quantity: number;
  product: ProductMeta[];
  variant: ProductVariantMeta[];
}
