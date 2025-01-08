<?php

namespace App\Models;

use App\Models\Traits\BelongsToItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcurementItem extends Model
{
    use HasFactory, BelongsToItem;

    public function procurement() : BelongsTo
    {
        return $this->belongsTo(Procurement::class);
    }

}
