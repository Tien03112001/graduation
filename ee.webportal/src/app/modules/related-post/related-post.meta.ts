import { PostMeta } from '../post/post.meta';

export class RelatedPostMeta {
  id: number;
  post_id: number;
  related_id: number;
  order: number;
  post: PostMeta;
  relation: PostMeta;
  existsRelations: RelatedPostMeta[];
}
