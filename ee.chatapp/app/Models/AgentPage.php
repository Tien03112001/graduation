<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentPage extends Model
{
    protected $table = 'agent_page';

    public function agent_page()
    {
        return $this->belongsTo(Agent::class, 'agent_id', 'id');
    }
}
