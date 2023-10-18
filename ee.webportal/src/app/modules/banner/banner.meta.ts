import {BannerGroupMeta} from '../banner-group/banner-group.meta';

export class BannerMeta {
  id: number;
  name: number;
  image: string;
  image_full_path: string;
  href: string;
  description: string;
  order: number;
  group: BannerGroupMeta;
  alt: string;
  summary: string;
  group_id: number;
  // published: boolean;
}
