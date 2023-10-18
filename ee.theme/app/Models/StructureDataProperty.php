<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StructureDataProperty
 *
 * @property int $id
 * @property int $type_id
 * @property string $name
 * @property string $value_type
 * @property string|null $value
 * @property string|null $possible_values
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StructureDataProperty newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StructureDataProperty newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StructureDataProperty query()
 * @method static \Illuminate\Database\Eloquent\Builder|StructureDataProperty whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StructureDataProperty whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StructureDataProperty whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StructureDataProperty wherePossibleValues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StructureDataProperty whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StructureDataProperty whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StructureDataProperty whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StructureDataProperty whereValueType($value)
 * @mixin \Eloquent
 */
class StructureDataProperty extends Model
{
    use HasFactory;
    protected $guarded = [];
}
