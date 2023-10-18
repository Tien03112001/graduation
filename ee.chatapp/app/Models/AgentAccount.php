<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentAccount extends Model
{
    use HasFactory;

    protected $connection = 'system';
    protected $table = 'users';

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
