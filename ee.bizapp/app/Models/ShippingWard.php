<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingWard extends Model
{
    protected $connection = 'web_system';
    protected $table = 'wards';

    protected $guarded = [];

    public function district()
    {
        return $this->belongsTo(ShippingDistrict::class, 'district_id');
    }

    public function wardByUnit()
    {
        return $this->hasOne(ShippingWardByUnit::class, 'ward_id');
    }
}
