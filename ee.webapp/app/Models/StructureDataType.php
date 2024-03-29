<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\StructureDataType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StructureDataType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StructureDataType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StructureDataType query()
 * @method static \Illuminate\Database\Eloquent\Builder|StructureDataType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StructureDataType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StructureDataType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StructureDataType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StructureDataType extends Model
{
    use HasFactory;

    protected $guarded = [];
}
