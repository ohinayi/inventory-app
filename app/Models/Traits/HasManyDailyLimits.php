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

     /**
     * Define a one-to-many relationship.
     *
     * @param  string  $related
     * @param  string|null  $foreignKey
     * @param  string|null  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    abstract public function hasMany($related, $foreignKey = null, $localKey = null);
    
}
