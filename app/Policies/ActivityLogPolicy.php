<?php

namespace App\Policies;

use App\Models\User;

class ActivityLogPolicy
{

    public function before(User $user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }

    public function viewAny(User $user): bool
    {
        return $user->can('viewAny.Logs');
    }
}
