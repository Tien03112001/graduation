<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SystemUser
 *
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUser whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUser wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUser whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemUser whereUsername($value)
 * @mixin \Eloquent
 */
class SystemUser extends Model
{
    use HasFactory;
    protected $guarded = [];
}
