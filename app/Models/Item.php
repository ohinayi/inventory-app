<?php

namespace App\Models;

use App\Models\Traits\HasManyConsumptions;
use App\Models\Traits\HasManyDailyLimits;
use App\Models\Traits\HasManyProcurementItems;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Item extends Model
{
    use HasFactory, HasManyConsumptions, HasManyDailyLimits, HasManyProcurementItems;


    // public function procurements(): HasManyThrough
    // {
    //     return $this->hasManyThrough(Procurement::class, ProcurementItem::class);
    // }
    
    
}
