<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $connection = 'web_system';
    protected $table = 'roles';
}
