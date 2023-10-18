<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;


class TrackingEvent extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'logging';
    protected $collection = 'tracking_events';
}
