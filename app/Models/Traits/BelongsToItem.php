<?php

namespace App\Models\Traits;

use App\Models\Item;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToItem
{
    
    public function item() : BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

     /**
     * Define an inverse one-to-one or many relationship.
     *
     * @param  string  $related
     * @param  string|null  $foreignKey
     * @param  string|null  $ownerKey
     * @param  string|null  $relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    abstract public function belongsTo($related, $foreignKey = null, $ownerKey = null, $relation = null, );

}
