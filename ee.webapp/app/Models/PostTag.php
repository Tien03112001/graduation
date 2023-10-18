<?php

namespace App\Models;

use App\Utils\StringUtil;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;


/**
 * App\Models\PostTag
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Article|null $article
 * @property-read mixed $full_path
 * @property-read \App\Models\MetaData|null $meta
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Post[] $posts
 * @property-read int|null $posts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StructureData[] $structured_datas
 * @property-read int|null $structured_datas_count
 * @method static \Illuminate\Database\Eloquent\Builder|PostTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostTag query()
 * @method static \Illuminate\Database\Eloquent\Builder|PostTag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostTag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostTag whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostTag whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PostTag extends Model
{
    protected $table = 'post_tags';
    protected $guarded = [];

    protected $appends = [
        'full_path'
    ];

    public function getFullPathAttribute()
    {
        return StringUtil::joinPaths(
            Config::get('app.web_url'),
            'post_tags',
            $this->attributes['slug']
        );
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tag_mapping', 'post_id', 'tag_id');
    }

    public function article()
    {
        return $this->morphOne(Article::class, 'articleable');
    }

    public function meta()
    {
        return $this->morphOne(MetaData::class, 'metaable');
    }

    public function structured_datas()
    {
        return $this->morphMany(StructureData::class, 'structureble');
    }
}
