<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DynamicTableColumn
 *
 * @property int $id
 * @property int $table_id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DynamicTable|null $table
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableColumn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableColumn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableColumn query()
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableColumn whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableColumn whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableColumn whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableColumn whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableColumn whereTableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableColumn whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @property string|null $type
 * @method static \Illuminate\Database\Eloquent\Builder|DynamicTableColumn whereType($value)
 */
class DynamicTableColumn extends Model
{
    protected $table = 'dynamic_table_columns';
    protected $guarded = [];

    public function table()
    {
        return $this->belongsTo(DynamicTable::class, 'table_id');
    }
}
