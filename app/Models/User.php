<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Traits\HasManyConsumptions;
use App\Models\Traits\HasManyDailyLimits;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Panel;

class User extends Authenticatable  implements FilamentUser
{
    use HasFactory, Notifiable, HasManyConsumptions, HasManyDailyLimits;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function procurements(){
        return $this->hasMany(Procurement::class);
    }

    public function consumptionRequests(){
        return $this->hasMany(ConsumptionRequest::class);
    }

    public function vouchers(){
        return $this->hasMany(Voucher::class);
    }

    public function user(){
        return $this->belongsTo(user::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role == 'admin';
    }
}
