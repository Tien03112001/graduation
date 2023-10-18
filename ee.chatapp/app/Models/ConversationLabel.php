<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ConversationLabel
 *
 * @property int $id
 * @property string $name
 * @property int $creator_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ConversationLabel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConversationLabel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConversationLabel query()
 * @method static \Illuminate\Database\Eloquent\Builder|ConversationLabel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversationLabel whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversationLabel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversationLabel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConversationLabel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ConversationLabel extends Model
{
    use HasFactory;
    protected $guarded = [];
}
