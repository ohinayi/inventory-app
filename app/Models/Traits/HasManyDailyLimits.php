<?php

namespace App\Models\Traits;

use App\Models\DailyLimit;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyDailyLimits
{
    public function daily_limits() : HasMany
    {
        return $this->hasMany(DailyLimit::class);
    }
    abstract public function hasMany($related, $foreignKey = null, $localKey = null) : HasMany;
    
}
