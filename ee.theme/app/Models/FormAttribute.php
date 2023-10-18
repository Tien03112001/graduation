<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FormAttribute
 *
 * @property int $id
 * @property int $form_id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\FormData|null $form
 * @property-read \App\Models\FormValue|null $form_value
 * @method static \Illuminate\Database\Eloquent\Builder|FormAttribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FormAttribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FormAttribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|FormAttribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormAttribute whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormAttribute whereFormId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormAttribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormAttribute whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormAttribute whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class FormAttribute extends Model
{
    protected $table = 'form_attributes';
    protected $guarded = [];

    public function form()
    {
        return $this->belongsTo(FormData::class, 'form_id');
    }

    public function form_value()
    {
        return $this->hasOne(FormValue::class, 'attribute_id');
    }
}
