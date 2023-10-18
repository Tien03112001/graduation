<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleOrderDetail extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variant()
    {
        return $this->hasOne(ProductVariant::class,'id', 'variant_id');
    }

}
