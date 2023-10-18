<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FormData
 *
 * @property int $id
 * @property int $form_id
 * @property int $attribute_id
 * @property string $form_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Form|null $form
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FormValue[] $form_value
 * @property-read int|null $form_value_count
 * @method static \Illuminate\Database\Eloquent\Builder|FormData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FormData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FormData query()
 * @method static \Illuminate\Database\Eloquent\Builder|FormData whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormData whereFormId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormData whereFormName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormData whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FormValue[] $form_values
 * @property-read int|null $form_values_count
 */
class FormData extends Model
{
    protected $table = 'form_data';
    protected $guarded = [];

    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id');
    }

    public function form_values()
    {
        return $this->hasMany(FormValue::class, 'data_id');
    }
}
