<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CustomerProfile
 *
 * @property int $id
 * @property int|null $customer_id
 * @property string|null $name
 * @property string|null $facebook_uuid
 * @property string|null $zalo_uuid
 * @property string|null $telegram_uuid
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $dob
 * @property string|null $id_number
 * @property string|null $address
 * @property string|null $province
 * @property string|null $district
 * @property string|null $ward
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProfile whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProfile whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProfile whereDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProfile whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProfile whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProfile whereFacebookUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProfile whereIdNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProfile whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProfile wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProfile whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProfile whereTelegramUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProfile whereWard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProfile whereZaloUuid($value)
 * @mixin \Eloquent
 */
class CustomerProfile extends Model
{
    use HasFactory;
    protected $guarded = [];
}
