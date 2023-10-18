<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $connection = 'web_system';
    protected $table = 'articles';
}
