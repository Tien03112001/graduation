<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookFanpage extends Model
{
    protected $connection = 'chat';
    protected $table = 'facebook_fanpages';
    protected $guarded = [];
}
