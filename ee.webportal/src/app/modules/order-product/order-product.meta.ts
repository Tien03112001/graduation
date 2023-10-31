import {ProductMeta} from '../product/product.meta';

export class OrderProductMeta {

  product_id: number;
  id: number;
  product_name: string;
  size: string;
  price: number;
  quantity: number;
  amount: number;
  // products: ProductMeta[];
  existsProducts: OrderProductMeta[];
}
