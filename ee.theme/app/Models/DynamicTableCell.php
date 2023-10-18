<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DynamicTableCell
 *
 * @property int $id
 * @property int $table_id
 * @property int $column_id
 * @property int $row_id
 * @property string $cell_value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DynamicTable|null $table
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableCell newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableCell newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableCell query()
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableCell whereCellValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableCell whereColumnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableCell whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableCell whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableCell whereRowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableCell whereTableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableCell whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @property-read \App\Models\DynamicTableColumn|null $column
 */
class DynamicTableCell extends Model
{
    protected $table = 'dynamic_table_cells';
    protected $guarded = [];

    public function table()
    {
        return $this->belongsTo(DynamicTable::class, 'table_id');
    }

    public function column()
    {
        return $this->belongsTo(DynamicTableColumn::class, 'column_id');
    }

}
