import {MetaDataMeta} from '../meta-data/meta-data.meta';
import {PhotoMeta} from '../photo-CRUD/photo.meta';
import {ArticleMeta} from '../article/article.meta';
import {StructuredDataMeta} from '../structured-data/structured-data.meta';
import {GalleryMeta} from '../gallery-CRUD/gallery.meta';
import {Product_categoryMeta} from '../product_category/product_category.meta';

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
  related_products: ProductMeta[];
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

  created_at: string;
  updated_at: string;
}
