<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingDistrictByUnit extends Model
{
    //
    protected $guarded = [];

    public function district()
    {
        return $this->belongsTo(ShippingDistrict::class, 'district_id');
    }
}
