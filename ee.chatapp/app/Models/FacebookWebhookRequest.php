<?php

namespace App\Models;


use Jenssegers\Mongodb\Eloquent\Model;

class FacebookWebhookRequest extends Model
{
    protected $connection = 'logging';
    protected $guarded = [];
}
