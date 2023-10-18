<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CustomerRank
 *
 * @property int $id
 * @property string $name
 * @property int $priority
 * @property float $min_amount_spent
 * @property float $min_purchases_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerRank newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerRank newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerRank query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerRank whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerRank whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerRank whereMinAmountSpent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerRank whereMinPurchasesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerRank whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerRank wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerRank whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CustomerRank extends Model
{
    use HasFactory;
    protected $guarded = [];
}
