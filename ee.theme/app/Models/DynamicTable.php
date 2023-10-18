<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DynamicTable
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DynamicTableCell[] $cells
 * @property-read int|null $cells_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DynamicTableColumn[] $columns
 * @property-read int|null $columns_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DynamicTableRow[] $rows
 * @property-read int|null $rows_count
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTable query()
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTable whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTable whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTable whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTable whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class DynamicTable extends Model
{
    protected $table = 'dynamic_tables';
    protected $guarded = [];

    public function columns()
    {
        return $this->hasMany(DynamicTableColumn::class, 'table_id');
    }

    public function rows()
    {
        return $this->hasMany(DynamicTableRow::class, 'table_id');
    }

    public function cells()
    {
        return $this->hasMany(DynamicTableCell::class, 'table_id');
    }
}
