<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\JobPostingCv
 *
 * @method static \Illuminate\Database\Eloquent\Builder|JobPostingCv newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JobPostingCv newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JobPostingCv query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $job_posting_id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $cv_file
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|JobPostingCv whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPostingCv whereCvFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPostingCv whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPostingCv whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPostingCv whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPostingCv whereJobPostingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPostingCv whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPostingCv wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPostingCv whereUpdatedAt($value)
 */
class JobPostingCv extends Model
{
    use HasFactory;
    protected $guarded = [];
}
