import {InventoryProductMeta} from '../inventory-product/inventory-product.meta';
import { ProductVariantMeta } from '../product-variant/product-variant.meta';
import {PromotionMeta} from '../promotion/promotion.meta';

export class ProductMeta {
  id: number;
  code: string;
  name: string;
  image: string;
  full_path: string;
  price: number;
  sale_price: number;
  created_at: string;
  updated_at: string;
  inventories: InventoryProductMeta[];
  variants: ProductVariantMeta[];
}
