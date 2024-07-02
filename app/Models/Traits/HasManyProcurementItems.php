<?php

namespace App\Models\Traits;

use App\Models\Procurement;
use App\Models\ProcurementItem;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyProcurementItems
{
    public function procurement_items() : HasMany
    {
        return $this->hasMany(ProcurementItem::class);
    }
    abstract public function hasMany($related, $foreignKey = null, $localKey = null) : HasMany;
    
}
