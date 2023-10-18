<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingUnit extends Model
{
    protected $guarded = [];

    protected $hidden = [
        'username',
        'password',
        'endpoint',
        'config',
        'token',
        'class_name'
    ];

    protected $casts = [
        'config' => 'array'
    ];


}
