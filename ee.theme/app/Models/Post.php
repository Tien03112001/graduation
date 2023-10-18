<?php

namespace App\Models;

use App\Utils\StringUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * App\Models\Post
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $category_id
 * @property string $category_slug
 * @property bool $published
 * @property int $order
 * @property string|null $summary
 * @property string|null $image
 * @property string|null $alt
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Article|null $article
 * @property-read \App\Models\PostCategory|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Gallery|null $gallery
 * @property-read mixed $created_date
 * @property-read mixed $full_path
 * @property-read \App\Models\MetaData|null $meta
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RelatedPost[] $related_posts
 * @property-read int|null $related_posts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StructureData[] $structured_datas
 * @property-read int|null $structured_datas_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PostTag[] $tags
 * @property-read int|null $tags_count
 * @method static \Illuminate\Database\Eloquent\Builder|Post draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post published()
 * @method static \Illuminate\Database\Eloquent\Builder|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCategorySlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @property string|null $book_data
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereBookData($value)
 * @property string|null $reserve_at
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereReserveAt($value)
 * @property-read mixed $amp_path
 */
class Post extends Model
{
    protected $table = 'posts';
    protected $guarded = [];

    protected $casts = [
        'published' => 'boolean'
    ];

    protected $appends = [
        'full_path',
    ];

    //Attributes
    public function getFullPathAttribute()
    {
        return StringUtil::joinPaths(config('app.url'),
            'post_categories', $this->attributes['category_slug'],
            'posts', $this->attributes['slug']
        );
    }

    public function getAmpPathAttribute()
    {
        return StringUtil::joinPaths(config('app.url'), 'amp', 'post_categories', $this->attributes['category_slug'], 'posts', $this->attributes['slug']);
    }

    public function getCreatedDateAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->toDateString();
    }

    //Relations
    public function article()
    {
        return $this->morphOne(Article::class, 'articleable');
    }

    public function meta()
    {
        return $this->morphOne(MetaData::class, 'metaable');
    }

    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }


    public function structured_datas()
    {
        return $this->morphMany(StructureData::class, 'structureble');
    }

    public function tags()
    {
        return $this->belongsToMany(PostTag::class, 'post_tag_mapping', 'post_id', 'tag_id');
    }

    public function comments()
    {
        return $this->hasManyThrough(Comment::class, Article::class, 'articleable_id')
            ->where('articleable_type', array_search(static::class, Relation::morphMap()) ?: static::class);
    }

    public function public_comments()
    {
        return $this->comments()->where('published', true);
    }

    public function gallery()
    {
        return $this->morphOne(Gallery::class, 'galleryable');
    }

    public function related_posts()
    {
        return $this->hasMany(RelatedPost::class, 'post_id');
    }

    //Scopes
    public function scopePublished(Builder $q)
    {
        return $q->where('published', true);
    }

    public function scopeDraft(Builder $q)
    {
        return $q->where('published', false);
    }


}
