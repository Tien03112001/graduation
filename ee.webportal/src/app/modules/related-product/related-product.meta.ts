import { ProductMeta } from '../product/product.meta';

export class RelatedProductMeta {
  id: number;
  product_id: number;
  related_id: number;
  order: number;
  product: ProductMeta;
  relation: ProductMeta;
  existsRelations: RelatedProductMeta[];
}
