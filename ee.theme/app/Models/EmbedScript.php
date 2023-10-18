<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\EmbedScript
 *
 * @property int $id
 * @property int $type
 * @property int $priority
 * @property string $name
 * @property string $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EmbedScript newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmbedScript newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmbedScript query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmbedScript whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmbedScript whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmbedScript whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmbedScript whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmbedScript wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmbedScript whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmbedScript whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class EmbedScript extends Model
{
    protected $table = 'embed_scripts';
    protected $guarded = [];
}
