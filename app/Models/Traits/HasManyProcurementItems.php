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

     /**
     * Define a one-to-many relationship.
     *
     * @param  string  $related
     * @param  string|null  $foreignKey
     * @param  string|null  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    abstract public function hasMany($related, $foreignKey = null, $localKey = null) ;
    
}
