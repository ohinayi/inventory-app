<?php

namespace App\Models;

use App\Models\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory, BelongsToUser;

    public function voucherItems(){
        return $this->hasMany(VoucherItem::class);
    }
}
