<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Conversation
 *
 * @property int $id
 * @property int|null $customer_id
 * @property int|null $contact_id
 * @property int|null $agent_id
 * @property string|null $source_type
 * @property int|null $source_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ConversationLabel[] $labels
 * @property-read int|null $labels_count
 * @property-read \App\Models\ConversationSession|null $last_session
 * @property-read Model|\Eloquent $source
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereAgentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereSourceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Conversation extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function source()
    {
        return $this->morphTo('source');
    }

    public function last_session()
    {
        return $this->hasOne(ConversationSession::class, 'conversation_id')
            ->where('open_status', true);
    }

    public function labels()
    {
        return $this->belongsToMany(ConversationLabel::class, 'conversation_label', 'label_id', 'conversation_id')
            ->withPivot('creator_id');
    }

    public  function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public  function contact()
    {
        return $this->hasOne(CustomerContact::class, 'id', 'contact_id');
    }

    public  function messages()
    {
        return $this->hasMany(Message::class, 'conversation_id')->orderBy('created_at','desc');
    }

}
