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
  created_at: string;
  updated_at: string;
  product: ProductMeta;
}
