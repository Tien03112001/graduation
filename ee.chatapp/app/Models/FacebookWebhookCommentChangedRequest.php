<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class FacebookWebhookCommentChangedRequest extends Model
{
    protected $connection = 'logging';
    protected $guarded = [];
}
