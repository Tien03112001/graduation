import {MetaDataMeta} from '../meta-data/meta-data.meta';
import {ArticleMeta} from '../article/article.meta';

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
  full_path: string;
  order: number;
  article: ArticleMeta
}
