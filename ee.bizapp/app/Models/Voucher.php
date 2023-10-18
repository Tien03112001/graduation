<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $connection = 'web_system';
    protected $table = 'vouchers';
    protected $guarded = [];
}
