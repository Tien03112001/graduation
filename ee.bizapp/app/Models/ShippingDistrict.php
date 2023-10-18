<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingDistrict extends Model
{
    protected $connection = 'web_system';
    protected $table = 'districts';

    protected $guarded = [];

    public function wards()
    {
        return $this->hasMany(ShippingWard::class, 'district_id');
    }

    public function province()
    {
        return $this->belongsTo(ShippingProvince::class, 'province_id');
    }

    public function districtByUnit()
    {
        return $this->hasOne(ShippingDistrictByUnit::class, 'district_id');
    }

}
