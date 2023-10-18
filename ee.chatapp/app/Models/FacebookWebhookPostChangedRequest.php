<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class FacebookWebhookPostChangedRequest extends Model
{
    protected $connection = 'logging';
    protected $guarded = [];
}
