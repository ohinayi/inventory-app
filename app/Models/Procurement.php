<?php

namespace App\Models;

use App\Models\Traits\BelongsToUser;
use App\Models\Traits\HasManyProcurementItems;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Procurement extends Model
{
    use HasFactory, BelongsToUser;

    // public function user() : BelongsTo
    // {
    //     return $this->belongsTo(User::class);
    // }

    public function approved_by() : BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }

    public function procurementItems(){
        return $this->hasMany(ProcurementItem::class);
    }
}
