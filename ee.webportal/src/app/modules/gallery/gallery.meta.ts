import {PhotoMeta} from '../photo-CRUD/photo.meta';

export class GalleryMeta {
  id: number;
  name: string;
  description: string;
  published: boolean;
  images: PhotoMeta[];
  images_count: number;
  galleryable_type: string;
  galleryable_id: number;
  galleryable: any;
}
