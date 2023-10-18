<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FormValue
 *
 * @property int $id
 * @property int $form_id
 * @property int $attribute_id
 * @property int $data_id
 * @property string $form_name
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\FormAttribute|null $attribute
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FormData[] $form_data
 * @property-read int|null $form_data_count
 * @method static \Illuminate\Database\Eloquent\Builder|FormValue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FormValue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FormValue query()
 * @method static \Illuminate\Database\Eloquent\Builder|FormValue whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormValue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormValue whereDataId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormValue whereFormId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormValue whereFormName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormValue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormValue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormValue whereValue($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @property-read \App\Models\FormAttribute|null $form
 * @property-read \App\Models\FormAttribute|null $form_attribute
 */
class FormValue extends Model
{
    protected $table = 'form_values';
    protected $guarded = [];

    public function form()
    {
        return $this->belongsTo(FormAttribute::class, 'attribute_id');
    }

    public function form_attribute()
    {
        return $this->belongsTo(FormAttribute::class, 'attribute_id');
    }
}
