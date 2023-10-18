<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $connection = 'web_system';
    protected $table = 'order_items';
    protected $guarded = [];
    public function order(){
        return $this->hasMany(OrderDetail::class);
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }

    public function variant()
    {
        return $this->hasOne(ProductVariant::class,'id', 'variant_id');
    }
}
