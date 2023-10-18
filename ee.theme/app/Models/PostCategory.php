<?php

namespace App\Models;

use App\Utils\StringUtil;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PostCategory
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $parent_id
 * @property bool $published
 * @property string|null $summary
 * @property string|null $image
 * @property string|null $alt
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|PostCategory[] $children
 * @property-read int|null $children_count
 * @property-read mixed $full_path
 * @property-read \App\Models\MetaData|null $meta
 * @property-read PostCategory|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Post[] $posts
 * @property-read int|null $posts_count
 * @method static \Illuminate\Database\Eloquent\Builder|PostCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|PostCategory whereAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostCategory whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostCategory whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostCategory whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostCategory wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostCategory whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostCategory whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StructureData[] $structured_datas
 * @property-read int|null $structured_datas_count
 * @property-read \App\Models\Article|null $article
 */
class PostCategory extends Model
{
    protected $table = 'post_categories';
    protected $guarded = [];

    protected $casts = [
        'published' => 'boolean'
    ];

    protected $appends = [
        'full_path',
    ];

    public function getFullPathAttribute()
    {
        return StringUtil::joinPaths(config('app.url'), 'post_categories', $this->attributes['slug']);
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

    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    public function parent()
    {
        return $this->belongsTo(PostCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(PostCategory::class, 'parent_id');
    }
}
