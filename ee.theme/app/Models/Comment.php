<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Comment
 *
 * @property int $id
 * @property int $article_id
 * @property int $parent_id
 * @property string $author
 * @property string $content
 * @property int $published
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Comment[] $children
 * @property-read int|null $children_count
 * @property-read Comment|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @property string|null $book_data
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereBookData($value)
 * @property string|null $reserve_at
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereReserveAt($value)
 * @property float $rating
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereRating($value)
 */
class Comment extends Model
{
    protected $table = 'comments';
    protected $guarded = [];

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
