<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FacebookConversationLabel
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookConversationLabel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookConversationLabel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookConversationLabel query()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookConversationLabel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookConversationLabel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookConversationLabel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookConversationLabel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FacebookConversationLabel extends Model
{
    use HasFactory;
    protected $guarded = [];
}
