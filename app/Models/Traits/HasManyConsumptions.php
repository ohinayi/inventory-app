<?php

namespace App\Models\Traits;

use App\Models\Consumption;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyConsumptions
{
    public function consumptions() : HasMany
    {
        return $this->hasMany(Consumption::class);
    }
    
    abstract public function hasMany($related, $foreignKey = null, $localKey = null) : HasMany;
    
}
