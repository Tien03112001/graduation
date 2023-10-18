<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookProduct extends Model
{
    protected $connection = 'web_system';
    protected $table = 'facebook_products';
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(FacebookProductCategory::class, 'fb_product_category');
    }
}
