<?php

namespace App\Models;

use App\Utils\StringUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Page
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property bool $published
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Article|null $article
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Block[] $blocks
 * @property-read int|null $blocks_count
 * @property-read mixed $created_date
 * @property-read mixed $full_path
 * @property-read mixed $summary
 * @property-read \App\Models\MetaData|null $meta
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StructureData[] $structured_datas
 * @property-read int|null $structured_datas_count
 * @method static \Illuminate\Database\Eloquent\Builder|Page draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page published()
 * @method static \Illuminate\Database\Eloquent\Builder|Page query()
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @property int $is_system
 * @property-read mixed $view_html
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereIsSystem($value)
 */
class Page extends Model
{
    protected $table = 'pages';
    protected $guarded = [];

    protected $casts = [
        'published' => 'boolean'
    ];

    protected $appends = [
        'full_path'
    ];

    //Attributes
    public function getFullPathAttribute()
    {
        return StringUtil::joinPaths(config('app.url'), $this->attributes['slug']);
    }

    public function getCreatedDateAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->toDateString();
    }

    public function getSummaryAttribute()
    {
        $summary = $this->meta->description ?? strip_tags(html_entity_decode($this->attributes['content']));
        return mb_substr($summary, 0, 200) . '...';
    }

    public function getViewHtmlAttribute()
    {
        $html = '';
        foreach ($this->blocks as $block) {
            $html .= $block->widget->view_html;
        }
        return $html;
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

    public function structured_datas()
    {
        return $this->morphMany(StructureData::class, 'structureble');
    }

    public function blocks()
    {
        return $this->hasMany(Block::class);
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
