<?php

namespace App\Policies;

use App\Models\Obligation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ObligationPolicy
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
        return $user->can('obligations.viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Obligation $obligation): bool
    {
        return $user->can('obligations.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('obligations.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Obligation $obligation): bool
    {
        return $user->can('obligations.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Obligation $obligation): bool
    {
        return $user->can('obligations.delete');
    }
}
