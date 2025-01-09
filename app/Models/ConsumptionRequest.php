<?php

namespace App\Models;

use App\Models\Traits\BelongsToItem;
use App\Models\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumptionRequest extends Model
{
    use HasFactory, BelongsToUser,BelongsToItem;

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }

    protected static function booted()
    {
        static::updated(function ($request) {
            if ($request->status === 'approved' && $request->wasChanged('status')) {
                Consumption::create([
                    'user_id' => $request->user_id,
                    'item_id' => $request->item_id,
                    'quantity' => $request->quantity,
                    'consumed_at' => now(),
                    // 'consumption_request_id' => $request->id
                ]);
            }
        });
    }
}
