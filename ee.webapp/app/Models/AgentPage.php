<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentPage extends Model
{
    protected $connection = 'chat';
    protected $table = 'agent_page';
    protected $guarded = [];
    
    public function agent_page()
    {
        return $this->belongsTo(Agent::class, 'agent_id', 'id');
    }
}
