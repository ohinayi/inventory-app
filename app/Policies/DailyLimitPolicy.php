<?php

namespace App\Policies;

use App\Helpers\PolicyHelpers;
use App\Models\DailyLimit;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DailyLimitPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DailyLimit $dailyLimit): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return PolicyHelpers::keeperAndManager($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DailyLimit $dailyLimit): bool
    {
        return PolicyHelpers::managerOnly($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DailyLimit $dailyLimit): bool
    {
        return PolicyHelpers::adminOnly($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DailyLimit $dailyLimit): bool
    {
        return PolicyHelpers::adminOnly($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DailyLimit $dailyLimit): bool
    {
        return PolicyHelpers::adminOnly($user);
    }
}
