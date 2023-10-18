<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Block
 *
 * @property int $id
 * @property int $page_id
 * @property int $widget_id
 * @property string $widget_name
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Widget|null $widget
 * @method static \Illuminate\Database\Eloquent\Builder|Block newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Block newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Block query()
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereWidgetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Block whereWidgetName($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Block extends Model
{
    protected $table = 'blocks';
    protected $guarded = [];

    public function widget()
    {
        return $this->belongsTo(Widget::class);
    }
}
