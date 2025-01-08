<?php

namespace App\Helpers;

use App\Models\User;

class PolicyHelpers
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public  static function keeperOnly(User $user) : bool
    {
        return in_array($user->role, ['keeper', 'admin']);
    }

    public  static function keeperAndManager(User $user) : bool
    {
        return in_array($user->role, ['keeper', 'manager', 'admin']);
    }

    public  static function managerOnly(User $user) : bool
    {
        return in_array($user->role, ['manager', 'admin']);
    }

    public  static function adminOnly(User $user) : bool
    {
        return $user->role == 'admin';
    }


}
