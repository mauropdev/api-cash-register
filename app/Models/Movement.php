<?php

namespace App\Models;

use App\Services\MoneyService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movement extends Model
{
    use HasFactory;

    protected $fillable = [
        'movement_type_id',
        'bill_100000',
        'bill_50000',
        'bill_20000',
        'bill_10000',
        'bill_5000',
        'bill_2000',
        'bill_1000',
        'coin_1000',
        'coin_500',
        'coin_200',
        'coin_100',
        'coin_50'
    ];

    protected $appends = [
        'total_money'
    ];

    /**
     * @return int
     */
    public function getTotalMoneyAttribute(): int
    {
        return MoneyService::getTotalMoney($this->getAttributes());
    }

    /**
     * @return BelongsTo
     */
    public function movementType(): BelongsTo
    {
        return $this->belongsTo(MovementType::class);
    }


}
