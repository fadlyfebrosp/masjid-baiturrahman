<?php

namespace App\Policies;

use App\Models\User;
use App\Models\donasi_offline;
use Illuminate\Auth\Access\Response;

class DonasiOfflinePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, donasi_offline $donasiOffline): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, donasi_offline $donasiOffline): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, donasi_offline $donasiOffline): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, donasi_offline $donasiOffline): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, donasi_offline $donasiOffline): bool
    {
        return false;
    }
}
