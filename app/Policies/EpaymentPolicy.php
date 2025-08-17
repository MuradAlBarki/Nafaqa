<?php

namespace App\Policies;

use App\Models\Epayment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EpaymentPolicy
{

    public function before(User $user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('epayments.viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Epayment $epayment): bool
    {
        return $user->can('epayments.view');
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
    public function update(User $user, Epayment $epayment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Epayment $epayment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Epayment $epayment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Epayment $epayment): bool
    {
        return false;
    }
}
