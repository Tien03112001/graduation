<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DynamicTableRow
 *
 * @property int $id
 * @property int $table_id
 * @property int $column_id
 * @property string|null $row_value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DynamicTable|null $table
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableRow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableRow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableRow query()
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableRow whereColumnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableRow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableRow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableRow whereRowValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableRow whereTableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableRow whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DynamicTableCell[] $cells
 * @property-read int|null $cells_count
 */
class DynamicTableRow extends Model
{
    protected $table = 'dynamic_table_rows';
    protected $guarded = [];

    public function table()
    {
        return $this->belongsTo(DynamicTable::class, 'table_id');
    }


    public function cells()
    {
        return $this->hasMany(DynamicTableCell::class, 'row_id');
    }
}
