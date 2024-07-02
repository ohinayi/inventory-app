<?php

namespace App\Models;

use App\Models\Traits\HasManyProcurementItems;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Procurement extends Model
{
    use HasFactory, HasManyProcurementItems;

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
