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
