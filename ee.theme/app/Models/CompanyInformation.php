<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CompanyInformation
 *
 * @property int $id
 * @property string $name
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyInformation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyInformation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyInformation query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyInformation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyInformation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyInformation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyInformation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyInformation whereValue($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class CompanyInformation extends Model
{
    protected $table = 'company_information';
    protected $guarded = [];
}
