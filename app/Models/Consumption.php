<?php

namespace App\Models;

use App\Models\Traits\BelongsToUser;
use App\Models\Traits\BelongsToItem;
use App\Rules\AvailableQuantityRule;
use App\Rules\ExceedLimitRule;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Awobaz\Compoships\Compoships;
use Illuminate\Support\Facades\DB;

class Consumption extends Model
{
    use HasFactory, BelongsToUser,  BelongsToItem, Compoships;

    // public function limit(){
    //     return Attribute::get(function(){
    //         $this->dailyLimit->limit ??
    //     });
    // }

    public function daily_limit(): BelongsTo
    {
        return $this->belongsTo(DailyLimit::class, ['user_id', 'item_id'], ['user_id', 'item_id']);
        // return $this->belongsTo(DailyLimit::class, 'user_id', 'item_id');
    }

    protected function casts(): array
    {
        return [
            'consumed_at' => 'datetime',
        ];
    }


    protected static function booted(): void
    {
        parent::boot();
        static::saving(function (Consumption $consumption) {
            $originalQuantity = $consumption->getOriginal('quantity') ?? 0;

            $validator = validator($consumption->toArray(), [
                'quantity' => [
                    'required',
                    'numeric',
                    new ExceedLimitRule($consumption->user_id, $consumption->item_id, $consumption->consumed_at),
                    new AvailableQuantityRule($consumption->item_id),
                ],
            ]);

            if ($validator->fails()) {
                throw new \Illuminate\Validation\ValidationException($validator);
            }
            // ...
        });
        static::created(function (Consumption $consumption) {
            $quantityDifference = $consumption->quantity - ($consumption->getOriginal('quantity') ?? 0);

            DB::transaction(function () use ($consumption, $quantityDifference) {
                $item = Item::query()->find($consumption->item_id);
                $item->decrement('quantity', $quantityDifference);
            });
        });
    }
}
