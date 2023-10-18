<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Menu
 *
 * @property int $id
 * @property string $name
 * @property int $type_id
 * @property string|null $menuable_type
 * @property int|null $menuable_id
 * @property string $url
 * @property int $order
 * @property int $parent_id
 * @property int $menu_position_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Menu[] $children
 * @property-read int|null $children_count
 * @property-read Model|\Eloquent $menuable
 * @property-read Menu|null $parent
 * @property-read \App\Models\MenuPosition|null $position
 * @method static \Illuminate\Database\Eloquent\Builder|Menu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu query()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereMenuPositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereMenuableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereMenuableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereUrl($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @property string|null $icon
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereIcon($value)
 */
class Menu extends Model
{
    protected $table = 'menu';
    protected $guarded = [];

    public function position()
    {
        return $this->belongsTo(MenuPosition::class, 'menu_position_id');
    }

    public function menuable()
    {
        return $this->morphTo();
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }
}
