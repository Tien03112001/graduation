import {InventoryProductMeta} from '../inventory-product/inventory-product.meta';
import {ProductVariantMeta} from '../product-variant/product-variant.meta';

export class ProductMeta {
  id: number;
  code: string;
  name: string;
  image: string;
  price: number;
  sale_price: number;
  variant: ProductVariantMeta;
  inventories: InventoryProductMeta[];
}
