<?php

namespace App\Models;

use App\Models\Traits\BelongsToEmployee;
use App\Models\Traits\BelongsToItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consumption extends Model
{
    use HasFactory, BelongsToEmployee,  BelongsToItem;
}
