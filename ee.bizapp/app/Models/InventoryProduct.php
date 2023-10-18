<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryProduct extends Model
{
    protected $connection = 'mysql';

    protected $guarded = [];

    protected $appends = [
        'available_quantity'
    ];

    public function getAvailableQuantityAttribute()
    {
        return $this->quantity - $this->used_quantity;
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
