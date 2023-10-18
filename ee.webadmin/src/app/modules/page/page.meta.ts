import {MetaDataMeta} from '../meta-data/meta-data.meta';
import {ArticleMeta} from '../article/article.meta';
import {StructuredDataMeta} from '../structured-data/structured-data.meta';
import {BlockMeta} from '../block/block.meta';

export class PageMeta {
  id: number;
  name: string;
  slug: string;
  full_path: string;
  css: string;
  script: string;
  published: boolean;
  meta: MetaDataMeta;
  article: ArticleMeta;
  structure_datas: StructuredDataMeta[];

  blocks: BlockMeta[];
  blocks_count;
}
