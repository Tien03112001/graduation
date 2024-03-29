<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $connection = 'web_system';
    protected $guarded = [];
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}

