<?php

namespace App\Models;

use App\Utils\Caches\WidgetUtil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Widget
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $html
 * @property int|null $default
 * @property string|null $css
 * @property string|null $js
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Widget newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Widget newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Widget query()
 * @method static \Illuminate\Database\Eloquent\Builder|Widget whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Widget whereCss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Widget whereDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Widget whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Widget whereHtml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Widget whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Widget whereJs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Widget whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Widget whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @property bool $is_system
 * @property-read mixed $view_html
 * @method static \Illuminate\Database\Eloquent\Builder|Widget whereIsSystem($value)
 */
class Widget extends Model
{
    use HasFactory;
    protected $table = 'widgets';
    protected $guarded = [];

    protected $casts = [
        'is_system' => 'boolean'
    ];


    public function getViewHtmlAttribute()
    {
        return WidgetUtil::render($this);
    }
}
