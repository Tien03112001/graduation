<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingProvince extends Model
{
    protected $connection = 'web_system';
    protected $table = 'provinces';

    protected $guarded = [];

    public function districts()
    {
        return $this->hasMany(ShippingDistrict::class, 'province_id');
    }

    public function provinceByUnit()
    {
        return $this->hasOne(ShippingProvinceByUnit::class, 'province_id');
    }

}
