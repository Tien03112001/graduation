<?php

namespace App\Models;

use App\Utils\HtmlUtil;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Article
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string|null $author_name
 * @property string|null $author_url
 * @property string $articleable_type
 * @property int $articleable_id
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article query()
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereArticleableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereArticleableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereAuthorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereAuthorUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @property-read mixed $formatted_content
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read mixed $amp_content
 */
class Article extends Model
{
    protected $table = 'articles';
    protected $guarded = [];

    protected $appends = [
        'formatted_content'
    ];


    public function getFormattedContentAttribute()
    {
        return HtmlUtil::formatted_content($this);
    }

    public function getAmpContentAttribute()
    {
        return HtmlUtil::convertAMP($this);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'article_id');
    }
}
