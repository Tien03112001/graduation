<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Form
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FormAttribute[] $attributes
 * @property-read int|null $attributes_count
 * @property-read \App\Models\FormData|null $form_data
 * @method static \Illuminate\Database\Eloquent\Builder|Form newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Form newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Form query()
 * @method static \Illuminate\Database\Eloquent\Builder|Form whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Form whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Form whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Form whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Form whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FormAttribute[] $properties
 * @property-read int|null $properties_count
 */
class Form extends Model
{
    protected $table = 'forms';
    protected $guarded = [];

    public function properties()
    {
        return $this->hasMany(FormAttribute::class, 'form_id');
    }

    public function form_data()
    {
        return $this->hasOne(FormData::class, 'data_id');
    }
}
