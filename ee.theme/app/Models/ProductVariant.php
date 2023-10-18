<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;
    public function inventory_products()
    {
        return $this->hasMany(InventoryProduct::class,'variant_id');
    }

}
