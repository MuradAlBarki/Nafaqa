<?php

namespace App\Policies;

use App\Enums\PaymentStatusEnum;
use App\Models\Payment;
use App\Models\User;
use App\StatusEnum;
use Illuminate\Auth\Access\Response;

class PaymentPolicy
{

    public function before(User $user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }

    public function create(User $user): bool
    {
        return $user->can('payments.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Payment $payment): bool
    {
        return $user->can('payments.update');
    }

    public function pay(User $user, Payment $payment): bool
    {
        return $payment->status == PaymentStatusEnum::Entry;
    }

     public function changeStatus(User $user, User $model): bool
    {
        return $user->can('payments.changeStatus');
    }
}
