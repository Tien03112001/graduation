<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingProvinceByUnit extends Model
{
    protected $guarded = [];

    public function province()
    {
        return $this->belongsTo(ShippingProvince::class, 'province_id');
    }
}
