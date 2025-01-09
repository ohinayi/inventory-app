<?php

namespace App\Models;

use App\Models\Traits\BelongsToItem;
use App\Models\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumptionRequest extends Model
{
    use HasFactory, BelongsToUser,BelongsToItem;
}
