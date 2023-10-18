import {ProductMeta} from '../product/product.meta';

export class CartItemMeta {
  product: ProductMeta;
  variant_id: number;
  unit_price: number;
  quantity: number;
}
