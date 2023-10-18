<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Customer
 *
 * @property int $id
 * @property string $uuid
 * @property int $rank_id
 * @property float $total_amount_spent
 * @property int $purchases_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer wherePurchasesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereRankId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereTotalAmountSpent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUuid($value)
 * @mixin \Eloquent
 */
class Customer extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function contact() {
        return $this->hasOne(CustomerContact::class);
    }

    public function rank() {
        return $this->hasOne(CustomerRank::class, 'id','rank_id');
    }
}
