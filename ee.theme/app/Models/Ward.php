<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Ward
 *
 * @property int $id
 * @property int $province_id
 * @property int $district_id
 * @property string $name
 * @property string $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Ward newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ward newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ward query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ward whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ward whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ward whereDistrictId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ward whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ward whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ward whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ward whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\District|null $district
 * @property-read \App\Models\Province|null $province
 */
class Ward extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

}
