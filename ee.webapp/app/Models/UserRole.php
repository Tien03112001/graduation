<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserRole
 *
 * @property int $user_id
 * @property int $role_id
 * @property-read \App\Models\Role|null $user_role
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereUserId($value)
 * @mixin \Eloquent
 */
class UserRole extends Model
{
    protected $table = 'user_role';

    protected $guarded = [];

    public function user_role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
