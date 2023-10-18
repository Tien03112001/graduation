import {BannerMeta} from '../banner/banner.meta';

export class CacheMeta {
  id: number;
  name: string;
  code: string;
  parent_id: number;
  parent: CacheMeta;
  banners: BannerMeta[];
}
