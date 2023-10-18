<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookProductCategory extends Model
{
    protected $connection = 'web_system';
    protected $table = 'facebook_product_categories';
    protected $guarded = [];
}
