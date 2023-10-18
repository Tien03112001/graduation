<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $connection = 'web_system';
    protected $table = 'app_settings';
}
