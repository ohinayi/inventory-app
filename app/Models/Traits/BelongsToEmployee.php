<?php

namespace App\Models\Traits;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToEmployee
{
    
    public function employee(){
        return $this->belongsTo(Employee::class, 3);
    }

    abstract public function belongsTo($related, $foreignKey = null, $ownerKey = null, $relation = null, ) : BelongsTo;
}
