<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ShippingFeeTable
 *
 * @property int $id
 * @property int $province_id
 * @property int $district_id
 * @property int $ward_id
 * @property float $fee
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingFeeTable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingFeeTable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingFeeTable query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingFeeTable whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingFeeTable whereDistrictId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingFeeTable whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingFeeTable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingFeeTable whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingFeeTable whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingFeeTable whereWardId($value)
 * @mixin \Eloquent
 * @property-read \App\Models\District|null $district
 * @property-read \App\Models\Province|null $province
 * @property-read \App\Models\Ward|null $ward
 */
class ShippingFeeTable extends Model
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

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }
}
