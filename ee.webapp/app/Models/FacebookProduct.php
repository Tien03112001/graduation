<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookProduct extends Model
{
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(FacebookProductCategory::class, 'fb_product_category');
    }
}
