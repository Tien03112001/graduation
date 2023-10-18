<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingFee extends Model
{
    protected $connection = 'web_system';
    protected $table = 'shipping_fee_tables';
    protected $guarded = [];
}
