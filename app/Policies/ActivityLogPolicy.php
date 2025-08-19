<?php

namespace App\Policies;

use App\Models\Spatie\Activitylog\Models\Activity;
use App\Models\User;
use Illuminate\Auth\Access\Response;

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
        return $user->can('logs.viewAny');
    }
}
