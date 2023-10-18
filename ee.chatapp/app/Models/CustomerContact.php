<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CustomerContact
 *
 * @property int $id
 * @property int|null $customer_id
 * @property string|null $last_name
 * @property string|null $first_name
 * @property string|null $full_name
 * @property string|null $facebook_uuid
 * @property string|null $zalo_uuid
 * @property string|null $telegram_uuid
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $dob
 * @property string|null $id_number
 * @property string|null $locale
 * @property string|null $gender
 * @property string|null $timezone
 * @property string|null $address
 * @property string|null $province
 * @property string|null $district
 * @property string|null $ward
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerContact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerContact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerContact query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerContact whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerContact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerContact whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerContact whereDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerContact whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerContact whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerContact whereFacebookUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerContact whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerContact whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerContact whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerContact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerContact whereIdNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerContact whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerContact whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerContact wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerContact whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerContact whereTelegramUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerContact whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerContact whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerContact whereWard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerContact whereZaloUuid($value)
 * @mixin \Eloquent
 */
class CustomerContact extends Model
{
    use HasFactory;
    protected $guarded = [];
}
