import {MetaDataMeta} from '../meta-data/meta-data.meta';
import {PhotoMeta} from '../photo-CRUD/photo.meta';
import {PostCategoryMeta} from '../post-category/post-category.meta';

export class Product_categoryMeta {
  id: number;
  name: string;
  slug: string;
  parent_id: number;
  parent: Product_categoryMeta;
  category: Product_categoryMeta;
  published: boolean;
  meta: MetaDataMeta;
  summary: string;
  image: string;
  alt: string;
  image_full_path: string;
  full_path:string;
  order: number;
}
