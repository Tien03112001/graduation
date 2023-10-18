<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function inventory_products()
    {
        return $this->hasOne(InventoryProduct::class, 'variant_id');
    }
}
