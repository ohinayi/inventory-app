<?php

namespace App\Policies;

use App\Helpers\PolicyHelpers;
use App\Models\Consumption;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
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
    public function view(User $user, Consumption $consumption): bool
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
    public function update(User $user, Consumption $consumption): bool
    {
        return PolicyHelpers::managerOnly($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Consumption $consumption): bool
    {
        return PolicyHelpers::adminOnly($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Consumption $consumption): bool
    {
        //
        return PolicyHelpers::adminOnly($user);

    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Consumption $consumption): bool
    {
        return PolicyHelpers::adminOnly($user);

        //
    }
}