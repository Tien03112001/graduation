<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MenuPosition
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Menu[] $menus
 * @property-read int|null $menus_count
 * @method static \Illuminate\Database\Eloquent\Builder|MenuPosition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuPosition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuPosition query()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuPosition whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuPosition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuPosition whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuPosition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuPosition whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuPosition whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class MenuPosition extends Model
{
    protected $table = 'menu_positions';
    protected $guarded = [];

    public function menus()
    {
        return $this->hasMany(Menu::class, 'menu_position_id');
    }

}
