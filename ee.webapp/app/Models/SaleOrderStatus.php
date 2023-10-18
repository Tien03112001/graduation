<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrderStatus extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'bizapp';
}
