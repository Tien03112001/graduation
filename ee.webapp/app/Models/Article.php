<?php

namespace App\Models;

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
 * @property-read Model|\Eloquent $articleable
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
 * @mixin \Eloquent
 */
class Article extends Model
{
    protected $table = 'articles';
    protected $guarded = [];

    public function articleable()
    {
        return $this->morphTo();
    }
}
