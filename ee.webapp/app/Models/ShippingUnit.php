<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ShippingStore;
use App\Models\ShippingService;


class ShippingUnit extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'bizapp';

    public function shipping_strores()
    {
        return $this->hasMany(ShippingStore::class, 'unit_id');
    }
    public function shipping_services()
    {
        return $this->hasMany(ShippingService::class, 'unit_id');
    }
}
