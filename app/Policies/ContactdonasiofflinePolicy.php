<?php

namespace App\Policies;

use App\Models\User;
use App\Models\contactdonasioffline;
use Illuminate\Auth\Access\Response;

class ContactdonasiofflinePolicy
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
    public function view(User $user, contactdonasioffline $contactdonasioffline): bool
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
    public function update(User $user, contactdonasioffline $contactdonasioffline): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, contactdonasioffline $contactdonasioffline): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, contactdonasioffline $contactdonasioffline): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, contactdonasioffline $contactdonasioffline): bool
    {
        return false;
    }
}
