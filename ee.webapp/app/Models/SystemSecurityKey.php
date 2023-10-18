<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SystemSecurityKey
 *
 * @property int $id
 * @property string $name
 * @property string $algorithm
 * @property string $iv
 * @property string $encryptionKey
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SystemSecurityKey newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemSecurityKey newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemSecurityKey query()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemSecurityKey whereAlgorithm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemSecurityKey whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemSecurityKey whereEncryptionKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemSecurityKey whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemSecurityKey whereIv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemSecurityKey whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemSecurityKey whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SystemSecurityKey extends Model
{
    use HasFactory;
    protected $guarded = [];
}
