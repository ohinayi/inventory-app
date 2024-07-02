<?php

namespace App\Models;

use App\Models\Traits\HasManyConsumptions;
use App\Models\Traits\HasManyDailyLimits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory, HasManyConsumptions, HasManyDailyLimits;

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
