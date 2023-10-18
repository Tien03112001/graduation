<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $connection = 'web_system';
    protected $table = 'product_categories'; 
}
