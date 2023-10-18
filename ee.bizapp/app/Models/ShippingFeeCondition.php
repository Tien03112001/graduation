<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingFeeCondition extends Model
{
    protected $casts = [
        'enable' => 'boolean'
    ];
}
