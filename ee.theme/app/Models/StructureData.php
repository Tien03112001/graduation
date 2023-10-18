<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StructureData
 *
 * @property int $id
 * @property string $code
 * @property string $content
 * @property string $structureble_type
 * @property int $structureble_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StructureData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StructureData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StructureData query()
 * @method static \Illuminate\Database\Eloquent\Builder|StructureData whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StructureData whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StructureData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StructureData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StructureData whereStructurebleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StructureData whereStructurebleType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StructureData whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class StructureData extends Model
{
    protected $table = 'structure_data';
}
