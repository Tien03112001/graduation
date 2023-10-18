<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Agent extends Model
{
    protected $connection = 'chat';
    protected $table = 'agents';
    protected $guarded = [];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function fanpages()
    {
        return $this->belongsToMany(FacebookFanpage::class, 'agent_page', 'agent_id', 'page_id');
    }
}
