<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToItem
{
    
    public function item() : BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    abstract public function belongsTo($related, $foreignKey = null, $ownerKey = null, $relation = null) : BelongsTo;

}
