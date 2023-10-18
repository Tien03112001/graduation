<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FacebookAdminAccount
 *
 * @property int $id
 * @property string $name
 * @property string $short_access_token
 * @property string $long_access_token
 * @property string $short_expired_at
 * @property string $long_expired_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookAdminAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookAdminAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookAdminAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookAdminAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookAdminAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookAdminAccount whereLongAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookAdminAccount whereLongExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookAdminAccount whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookAdminAccount whereShortAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookAdminAccount whereShortExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookAdminAccount whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FacebookAdminAccount extends Model
{
    use HasFactory;
    protected $guarded = [];
}
