import {MetaDataMeta} from '../meta-data/meta-data.meta';
import {PhotoMeta} from '../photo-CRUD/photo.meta';
import {ArticleMeta} from '../article/article.meta';
import {ProductTagMeta} from '../product-tag/product-tag.meta';
import {StructuredDataMeta} from '../structured-data/structured-data.meta';
import {Product_categoryMeta} from '../product_category/product_category.meta';
import {GalleryMeta} from '../gallery/gallery.meta';
import { RelatedProductMeta } from '../related-product/related-product.meta';
import {PromotionMeta} from '../promotion/promotion.meta';

export class ProductMeta {
  id: number;
  name: string;
  slug: string;
  full_path: string;
  category_id: number;
  published: boolean;
  meta: MetaDataMeta;
  photo: PhotoMeta;
  category: Product_categoryMeta;
  tags: ProductTagMeta[];
  related_products: RelatedProductMeta[];
  structure_datas: StructuredDataMeta[];
  summary: string;
  content: string;
  image_full_path: string;
  alt: string;
  image: string;
  article: ArticleMeta;
  category_slug: string;
  gallery: GalleryMeta;
  order: number;
  price: number;
  sale_price: number;
  promotions: PromotionMeta;
  created_at: string;
  updated_at: string;
}
