import {MetaDataMeta} from '../meta-data/meta-data.meta';
import {PhotoMeta} from '../photo-CRUD/photo.meta';

export class PostCategoryMeta {
  id: number;
  name: string;
  slug: string;
  full_path: string;
  parent_id: number;
  parent_path: string;
  parent: PostCategoryMeta;
  published: boolean;
  meta: MetaDataMeta;
  photo: PhotoMeta;
  summary: string;
  image: string;
  alt: string;
  image_full_path: string;
  order: number;
  category: PostCategoryMeta;
}
