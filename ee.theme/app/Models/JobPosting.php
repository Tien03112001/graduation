<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\JobPosting
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string|null $image
 * @property string $job_location
 * @property float|null $base_salary_min
 * @property float|null $base_salary_max
 * @property string $unit_currency
 * @property string $unit_time
 * @property string $date_posted
 * @property string|null $valid_through
 * @property int $quantity
 * @property string|null $type
 * @property int $published
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting query()
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting whereBaseSalaryMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting whereBaseSalaryMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting whereDatePosted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting whereJobLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting whereUnitCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting whereUnitTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting whereValidThrough($value)
 * @mixin \Eloquent
 * @property string $employment_type
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting whereEmploymentType($value)
 */
class JobPosting extends Model
{
    use HasFactory;

    protected $guarded = [];
}
