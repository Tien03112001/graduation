import {MetaDataMeta} from '../meta-data/meta-data.meta';
import {PhotoMeta} from '../photo-CRUD/photo.meta';
import {PostCategoryMeta} from '../post-category/post-category.meta';
import {ArticleMeta} from '../article/article.meta';
import {StructuredDataMeta} from '../structured-data/structured-data.meta';
import {GalleryMeta} from '../gallery-CRUD/gallery.meta';

export class PostMeta {
  id: number;
  name: string;
  slug: string;
  full_path: string;
  category_id: number;
  published: boolean;
  reserve_at: any;
  meta: MetaDataMeta;
  photo: PhotoMeta;
  category: PostCategoryMeta;
  related_posts: PostMeta[];
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

  created_at: string;
  updated_at: string;
}
