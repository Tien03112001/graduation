<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\RelatedPost
 *
 * @property int $id
 * @property int $post_id
 * @property int $related_id
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Post|null $post
 * @property-read \App\Models\Post|null $relation
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedPost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedPost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedPost query()
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedPost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedPost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedPost whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedPost wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedPost whereRelatedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedPost whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RelatedPost extends Model
{
    protected $table = 'related_posts';
    protected $guarded = [];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function relation()
    {
        return $this->belongsTo(Post::class, 'related_id');
    }

}
